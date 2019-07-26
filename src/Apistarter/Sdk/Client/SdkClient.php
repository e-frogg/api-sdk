<?php


namespace Apistarter\Sdk\Client;


use Apistarter\Sdk\Decorator\BodyDecoratorInterface;
use Apistarter\Sdk\Request\RequestWithBody;
use Apistarter\Sdk\Request\SdkRequestInterface;
use Apistarter\Sdk\Response\SingleObjectResponse;
use Efrogg\Collection\ObjectArrayAccess;
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
     * @var ApiClientInterface
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

    /**
     * SdkClient constructor.
     * @param ApiClientInterface $apiClient
     * @param SerializerInterface $serializer
     */
    public function __construct(ApiClientInterface $apiClient, SerializerInterface $serializer)
    {
        $this->apiClient = $apiClient;
        $this->setSerializer($serializer);
        parent::__construct();
    }

    public function execute(SdkRequestInterface $request)
    {

        $body = [];
        if ($request instanceof RequestWithBody) {
            //TODO : gestion des exceptions
            $body = $this->serializer->normalize(
                $this->getDecorateRequestBody($request->getBodyParams()),
                $request->getFormat()
            );
        }


        //TODO : gestion des exceptions
        $api_response = $this->apiClient->call(
            $request->getMethod(),
            $request->getEndPoint(),
            $body
        );


        $return_response_class = $request->getResponseClass();

        if (!class_exists($return_response_class)) {
            throw new LogicException("invalid response class $return_response_class");
        }
        if (is_subclass_of($return_response_class, SingleObjectResponse::class)) {

            // cas d'une réponse avec un seul type, on crée la réponse,
            // et on y injecte la propriété désérialisée, avec le bon type

            $return_response = new $return_response_class();

            $object_class = $return_response_class::getResponsePropertyType();
            $object = $this->serializer->deserialize(
                $api_response->getContent(),
                $object_class,
                $request->getFormat()
            );

            $return_response_property = $return_response_class::getResponsePropertyName();
            $return_response->$return_response_property = $this->getDecorateResponseBody($object);

            return $return_response;
        }

        $response_body = $api_response->getContent();

        if ($request->getMethod() === "DELETE") {
            $array_response_body = array('code' => $api_response->getStatusCode());
            if ($api_response->getStatusCode() !== 204) {
                throw new RuntimeException("Not 204 code for DELETE");
            }
            $response_body = json_encode($array_response_body);
        }

        // cas d'une réponse désérialisable directement (Response / Model)
        return $this->getDecorateResponseBody($this->serializer->deserialize(
            $response_body,
            $return_response_class,
            $request->getFormat()
        ));

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

}