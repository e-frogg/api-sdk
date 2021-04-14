<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 06/03/19
 * Time: 11:55
 */

namespace Apistarter\Sdk\Request;


use Apistarter\Sdk\Exception\SdkErrorData;
use Exception;

interface SdkRequestInterface
{
    public function getFormat(): string;

    public function getMethod();

    public function getUrl();

    public function getEndPoint();

    public function getResponseClass();

    public function getQueryParameters();

    public function getQueryParameterNames();

    public function dispatchCustomException(SdkErrorData $sdkErrorData, Exception $previous=null);
}