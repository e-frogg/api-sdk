<?php


namespace Apistarter\Sdk\Event;


class ResponseEvent extends RequestEvent
{
    /**
     * @var mixed
     */
    public $sdkResponse;
}