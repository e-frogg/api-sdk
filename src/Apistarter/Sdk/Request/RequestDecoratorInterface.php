<?php


namespace Apistarter\Sdk\Request;


interface RequestDecoratorInterface
{
    public function decorateEndpoint(string $endpoint,SdkRequestInterface $request);
    public function decorateQueryParameters(array $queryParameters,SdkRequestInterface $request);

}