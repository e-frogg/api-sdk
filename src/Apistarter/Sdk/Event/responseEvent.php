<?php


namespace Apistarter\Sdk\Event;


use Apistarter\Sdk\Request\SdkRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Contracts\EventDispatcher\Event;

class responseEvent extends Event
{

    /**
     * @var SdkRequestInterface
     */
    public $request;
    /**
     * @var ResponseInterface
     */
    public $apiResponse;
    /**
     * @var mixed
     */
    public $sdkResponse;
}