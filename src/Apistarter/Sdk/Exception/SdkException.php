<?php

namespace Apistarter\Sdk\Exception;

use Apistarter\Sdk\Model\SdkModel;
use Apistarter\Sdk\Request\AbstractRequest;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;


class SdkException extends \Exception
{

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

    /**
     * classe de l'erreur. Doit étendre SdkModel
     * @var string
     */
    protected $errorDataClass = SdkErrorData::class;

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasResponse(): bool
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
    public function getResponseBody(): string
    {
        return $this->response_body;
    }

    /**
     * @param string $response_body
     */
    public function setResponseBody(string $response_body)
    {
        $this->response_body = $response_body;
    }

    public function hasErrorData():bool
    {
        return ($this->hasResponseBody() && !empty($this->getErrorDataClass()));
    }

    public function getErrorData():SdkErrorData
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
    public function hasResponseData(): bool
    {
        return null !== $this->response_data;
    }

    /**
     * @return bool
     */
    public function hasResponseBody(): bool
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
    public function getRequest(): AbstractRequest
    {
        return $this->request;
    }

    /**
     * @return bool
     */
    public function hasRequest(): bool
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

}
