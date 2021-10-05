<?php

namespace Apistarter\Sdk\Exception;

use Apistarter\Sdk\Model\SdkModel;
use Apistarter\Sdk\Request\AbstractRequest;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;


class SdkException extends \Exception
{

    /** @var ResponseInterface */
    protected $api_response;

    /** @var ResponseInterface */
    protected $response;

    /** @var string */
    protected $response_body = '';

    /** @var array|null */
    protected $response_data;

    /** @var RequestException */
    protected $guzzle_request_exception;

    /** @var AbstractRequest */
    protected $request;

    /** @var string */
    protected $fullResponse;

    /**
     * classe de l'erreur. Doit étendre SdkModel
     * @var string
     */
    protected $errorDataClass = SdkErrorData::class;

    /**
     * @return ResponseInterface
     */
    public function getApiResponse()
    {
        return $this->api_response;
    }

    /**
     * @return bool
     */
    public function hasApiResponse()
    {
        return null !== $this->api_response;
    }

    /**
     * @param ResponseInterface $api_response
     */
    public function setApiResponse(ResponseInterface $api_response)
    {
        $this->api_response = $api_response;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    /**
     * @param string $response_body
     */
    public function setResponseBody($response_body)
    {
        $this->response_body = $response_body;
    }

    public function hasErrorData()
    {
        return ($this->hasResponseBody() && !empty($this->getErrorDataClass()));
    }

    public function getErrorData()
    {
        $className = $this->getErrorDataClass();
        return new $className(json_decode($this->getResponseBody(), true));
    }


    public function setGuzzleRequestException(RequestException $e)
    {
        $this->guzzle_request_exception;
        if ($e->hasResponse()) {
            $body = $e->getResponse()->getBody();
            $body->rewind();
            $this->setResponseBody($body->getContents());
            $this->setResponse($e->getResponse());
            $this->setResponseData(json_decode($this->getResponseBody()));
        }
    }

    /**
     * @return array|null
     */
    public function getResponseData()
    {
        return $this->response_data;
    }

    /**
     * @return bool
     */
    public function hasResponseData()
    {
        return null !== $this->response_data;
    }

    /**
     * @return bool
     */
    public function hasResponseBody()
    {
        return null !== $this->response_body;
    }

    /**
     * @param array|null $response_data
     */
    public function setResponseData($response_data)
    {
        $this->response_data = $response_data;
    }

    /**
     * @return AbstractRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return bool
     */
    public function hasRequest()
    {
        return null !== $this->request;
    }

    /**
     * @param AbstractRequest $request
     */
    public function setRequest(AbstractRequest $request)
    {
        $this->request = $request;
    }

    public function hasUuid()
    {
        return defined(get_class($this) . '::uuid');
    }

    public function getUuid()
    {
        if ($this->hasUuid()) {
            return constant(get_class($this) . '::uuid');
        }
        return null;
    }

    /**
     * @param string $errorDataClass
     * @return SdkException
     */
    public function setErrorDataClass($errorDataClass)
    {
        if (!is_subclass_of($errorDataClass, SdkModel::class)) {
            throw new \LogicException('errorClass ' . $errorDataClass . ' must extend SdkModel');
        }
        $this->errorDataClass = $errorDataClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorDataClass()
    {
        return $this->errorDataClass;
    }

    /**
     * @return string
     */
    public function getFullResponse(): string
    {
        return $this->fullResponse;
    }

    /**
     * @param string $fullResponse
     */
    public function setFullResponse(string $fullResponse): void
    {
        $this->fullResponse = $fullResponse;
    }

}
