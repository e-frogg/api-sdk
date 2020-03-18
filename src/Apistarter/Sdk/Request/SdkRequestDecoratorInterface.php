<?php


namespace Apistarter\Sdk\Request;


interface SdkRequestDecoratorInterface
{
    public function decorateEndpoint(string $endpoint,SdkRequestInterface $request);
    public function decorateQueryParameters(array $queryParameters,SdkRequestInterface $request);

}