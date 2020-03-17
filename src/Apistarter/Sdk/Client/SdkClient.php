<?php


namespace Apistarter\Sdk\Client;


use Apistarter\Sdk\Event\RequestErrorEvent;
use Apistarter\Sdk\Event\responseEvent;
use Apistarter\Sdk\Event\SdkClientEvent;
use Apistarter\Sdk\Decorator\BodyDecoratorInterface;
use Apistarter\Sdk\Request\RequestWithBody;
use Apistarter\Sdk\Request\SdkRequestInterface;
use Apistarter\Sdk\Response\SingleObjectResponse;
use Efrogg\Collection\ObjectArrayAccess;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use function GuzzleHttp\Psr7\stream_for;
use function is_array;
use function is_object;
use function is_string;
use LogicException;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SdkClient extends EventDispatcher implements SdkClientInterface
{
    /**
     * @var NormalizerInterface|SerializerInterface
     */
    protected $serializer;
    /**
     * @var Client
     */
    private $apiClient;

    /** @var BodyDecoratorInterface[] */
    protected $request_body_decorators = [];

    /** @var BodyDecoratorInterface[] */
    protected $response_body_decorators = [];
    /**
     * @var bool
     */
    private $debug = false;

    private $requestOptions = [];

    /**
     * @var String
     */
    private $responseHack;

    /**
     * SdkClient constructor.
     * @param Client $guzzleClient
     * @param SerializerInterface $serializer
     */
    public function __construct(Client $guzzleClient, SerializerInterface $serializer)
    {
        $this->apiClient = $guzzleClient;
        $this->setSerializer($serializer);
        parent::__construct();
    }

    public function execute(SdkRequestInterface $request)
    {
        $body = [];
        if ($request instanceof RequestWithBody && null !== ($bodyParams=$request->getBodyParams())) {
            //TODO : gestion des exceptions
            $body = $this->serializer->normalize(
                $this->getDecorateRequestBody($bodyParams),
                $request->getFormat()
            );
            if (!is_string($body)) {
                $body = json_encode($body);// TODO : ononononnono
            }
        }


        //TODO : gestion des exceptions
        try {
            $guzzleRequest = new Request($request->getMethod(), $request->getUrl());
            $options = $this->requestOptions;
            if (!empty($body)) {
                $options[RequestOptions::BODY] = $body;
            }

            $api_response = $this->apiClient->request(
                $request->getMethod(),
                $request->getUrl(),
                $options
            );
        } catch (GuzzleException $e) {
            // todo : gestion de l'exception (event ?)
            // fwd l'exception
            $errorEvent = new RequestErrorEvent();
            $errorEvent->request = $request;
            $errorEvent->exception = $e;
            $this->dispatch($errorEvent, SdkClientEvent::REQUEST_ERROR);

            throw new \Apistarter\Sdk\Exception\GuzzleException($e->getMessage(), $e->getCode());
        }


        $return_response_class = $request->getResponseClass();

        if (!class_exists($return_response_class)) {
            throw new LogicException("invalid response class $return_response_class");
        }
        if(!$this->hasResponseHack()) {
            $response_body = $api_response->getBody()->getContents();
        } else {
            $response_body = $this->getResponseHack();
            $this->setResponseHack(null);
        }

        if (is_subclass_of($return_response_class, SingleObjectResponse::class)) {
            /** @var SingleObjectResponse $return_response_class */
            // cas d'une réponse avec un seul type, on crée la réponse,
            // et on y injecte la propriété désérialisée, avec le bon type

            $return_response = new $return_response_class();

            $object_class = $return_response_class::getResponsePropertyType();

//            if(null === $object_class) {
//                $object=$response_body;
//
//            } else {
            try {
                $object = $this->serializer->deserialize(
                    $response_body,
                    $object_class,
                    $request->getFormat()
                );
            } catch (NotEncodableValueException $exception) {
                dd($request, $body, $response_body);
            }

            $return_response_property = $return_response_class::getResponsePropertyName();
            $return_response->$return_response_property = $this->getDecorateResponseBody($object);

            $this->dispatchResponseEvent($request, $api_response, $return_response);
            return $return_response;
        }


        if ('DELETE' === $request->getMethod()) {
            $array_response_body = ['code' => $api_response->getStatusCode()];
            if ($api_response->getStatusCode() !== 204) {
                throw new RuntimeException("Not 204 code for DELETE");
            }
            $response_body = json_encode($array_response_body);
        }

        // cas d'une réponse désérialisable directement (Response / Model)
        try {
            $decoratedResponse = $this->getDecorateResponseBody(
                $this->serializer->deserialize(
                    $response_body,
                    $return_response_class,
                    $request->getFormat()
                )
            );
        } catch (NotEncodableValueException $exception) {
            dd($request, $body, $response_body);
        }

        if(null !== $api_response) {
            $this->dispatchResponseEvent($request, $api_response, $decoratedResponse);
        }

        return $decoratedResponse;
    }

    /**
     * @param NormalizerInterface|SerializerInterface $serializer
     * @return self
     */
    public function setSerializer(SerializerInterface $serializer): self
    {
        $this->serializer = $serializer;
        return $this;
    }

    /**
     * @return NormalizerInterface|SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @param bool $debug
     * @return self
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param BodyDecoratorInterface $response_body_decorator
     * @return self
     */
    public function addResponseBodyDecorator(BodyDecoratorInterface $response_body_decorator): self
    {
        $this->response_body_decorators [] = $response_body_decorator;
        return $this;
    }

    /**
     * @param BodyDecoratorInterface $request_body_decorator
     * @return self
     */
    public function addRequestBodyDecorator(BodyDecoratorInterface $request_body_decorator): self
    {
        $this->request_body_decorators[] = $request_body_decorator;
        return $this;
    }

    private function getDecorateRequestBody($body)
    {
        /** @var BodyDecoratorInterface $decorator */
        foreach ($this->request_body_decorators as $decorator) {
            $body = $this->recursiveDecorate($body, $decorator);
        }
        return $body;
    }

    private function getDecorateResponseBody($body)
    {
        /** @var BodyDecoratorInterface $decorator */
        foreach ($this->response_body_decorators as $decorator) {
            $body = $this->recursiveDecorate($body, $decorator);
        }
        return $body;
    }

    public function recursiveDecorate($data, BodyDecoratorInterface $decorator)
    {
        if (is_object($data)) {
            // props publiques
            foreach ($data as $k => $subvalue) {
                $data->$k = $this->recursiveDecorate($subvalue, $decorator);
            }
            // props magiques
            if ($data instanceof ObjectArrayAccess) {
                foreach ($data->getData() as $k => $subvalue) {
                    $data->$k = $this->recursiveDecorate($subvalue, $decorator);
                }
            }
        } elseif (is_array($data)) {
            foreach ($data as $k => $subvalue) {
                $data[$k] = $this->recursiveDecorate($subvalue, $decorator);
            }
        } elseif (is_string($data)) {
            $data = $decorator->decorate($data);
        }
        return $data;
    }

    /**
     * options Guzzle passées au send
     * @param array $requestOptions
     * @return SdkClient
     * @see \GuzzleHttp\RequestOptions.
     */
    public function setRequestOptions(array $requestOptions): SdkClient
    {
        $this->requestOptions = $requestOptions;
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return array|string
     * @throws \Apistarter\Sdk\Exception\GuzzleException
     */
    public function __call($name, $arguments)
    {
        return $this->execute(...$arguments);
    }

    private function dispatchResponseEvent(
        SdkRequestInterface $request,
        ResponseInterface $apiResponse,
        $sdkResponse
    ) {
        $responseEvent = new responseEvent();
        $responseEvent->request = $request;
        $responseEvent->apiResponse = $apiResponse;
        $responseEvent->sdkResponse = $sdkResponse;
        $this->dispatch($responseEvent, SdkClientEvent::RESPONSE);
    }

    /**
     * @return String
     */
    public function getResponseHack()
    {
        return $this->responseHack;
    }

    /**
     * @param String $responseHack
     * @return SdkClient
     */
    public function setResponseHack($responseHack)
    {
        $this->responseHack = $responseHack;
        return $this;
    }

    /**
     * @return bool
     */
    private function hasResponseHack()
    {
        return null !== $this->responseHack;
    }


}