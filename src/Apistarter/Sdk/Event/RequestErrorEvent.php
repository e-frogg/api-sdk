<?php


namespace Apistarter\Sdk\Event;


use Apistarter\Sdk\Request\SdkRequestInterface;
use Exception;

class RequestErrorEvent
{
    public SdkRequestInterface $request;

    public Exception $exception;
}