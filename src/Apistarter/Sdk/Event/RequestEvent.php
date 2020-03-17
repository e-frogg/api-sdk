<?php


namespace Apistarter\Sdk\Event;


use Apistarter\Sdk\Request\SdkRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Contracts\EventDispatcher\Event;

class RequestEvent extends Event
{

    /**
     * @var SdkRequestInterface
     */
    public SdkRequestInterface $request;
    
    /**
     * @var ResponseInterface
     */
    public ResponseInterface $apiResponse;
}