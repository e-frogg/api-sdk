<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 06/03/19
 * Time: 11:54
 */

namespace Apistarter\Sdk\Request;


use Apistarter\Sdk\Exception\SdkErrorData;
use Exception;

class CustomRequest implements SdkRequestInterface
{
    /** @var string */
    protected $format;
    /** @var string */
    protected $method;
    /** @var string */
    protected $url;
    /** @var string */
    protected $endPoint;
    /** @var string */
    protected $responseClass;
    /** @var array */
    protected $queryParameters;

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     */
    public function setEndPoint(string $endPoint)
    {
        $this->endPoint = $endPoint;
    }

    /**
     * @return string
     */
    public function getResponseClass(): string
    {
        return $this->responseClass;
    }

    /**
     * @param string $responseClass
     */
    public function setResponseClass(string $responseClass)
    {
        $this->responseClass = $responseClass;
    }

    /**
     * @return array
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    /**
     * @param array $queryParameters
     */
    public function setQueryParameters(array $queryParameters)
    {
        $this->queryParameters = $queryParameters;
    }

    public function dispatchCustomException(SdkErrorData $sdkErrorData, Exception $previous = null)
    {
        // TODO: Implement dispatchCustomException() method.
    }
}