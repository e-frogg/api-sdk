<?php


namespace Apistarter\Sdk\Event;


use Exception;

class RequestErrorEvent extends RequestEvent
{
    /**
     * @var Exception
     */
    public $exception;
}