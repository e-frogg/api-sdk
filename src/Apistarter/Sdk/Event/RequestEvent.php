<?php


namespace Apistarter\Sdk\Event;


use Apistarter\Sdk\Request\SdkRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

class RequestEvent extends Event
{

    /**
     * @var SdkRequestInterface
     */
    public $request;
    /**
     * @var ResponseInterface
     */
    public $apiResponse;
}