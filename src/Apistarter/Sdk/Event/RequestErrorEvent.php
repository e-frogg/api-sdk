<?php


namespace Apistarter\Sdk\Event;


use Apistarter\Sdk\Request\SdkRequestInterface;
use Exception;
use Symfony\Component\EventDispatcher\Event;

class RequestErrorEvent extends Event
{
    /**
     * @var SdkRequestInterface
     */
    public $request;

    /**
     * @var Exception
     */
    public $exception;
}