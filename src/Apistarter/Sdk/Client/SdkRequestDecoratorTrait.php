<?php


namespace Apistarter\Sdk\Client;


use Apistarter\Sdk\Request\SdkRequestDecoratorInterface;

trait SdkRequestDecoratorTrait
{
    /**
     * @var SdkRequestDecoratorInterface[]
     */
    protected array $requestDecorators = [];

    /**
     * @param SdkRequestDecoratorInterface[] $requestDecorators
     * @return self
     */
    public function setRequestDecorators(array $requestDecorators): self
    {
        $this->requestDecorators = $requestDecorators;
        return $this;
    }

    /**
     * @param SdkRequestDecoratorInterface $requestDecorator
     * @return SdkRequestDecoratorTrait
     */
    public function addRequestDecorator(SdkRequestDecoratorInterface $requestDecorator): self
    {
        $this->requestDecorators[] = $requestDecorator;
        return $this;
    }

    /**
     * @return SdkRequestDecoratorInterface[]
     */
    public function getRequestDecorators(): array
    {
        return $this->requestDecorators;
    }

}