<?php


namespace Apistarter\Sdk\Client;


use Apistarter\Sdk\Request\RequestDecoratorInterface;

trait RequestDecoratorTrait
{
    /**
     * @var RequestDecoratorInterface[]
     */
    protected array $requestDecorators = [];

    /**
     * @param RequestDecoratorInterface[] $requestDecorators
     * @return self
     */
    public function setRequestDecorators(array $requestDecorators): self
    {
        $this->requestDecorators = $requestDecorators;
        return $this;
    }

    /**
     * @param RequestDecoratorInterface $requestDecorator
     * @return RequestDecoratorTrait
     */
    public function addRequestDecorator(RequestDecoratorInterface $requestDecorator): self
    {
        $this->requestDecorators[] = $requestDecorator;
        return $this;
    }

    /**
     * @return RequestDecoratorInterface[]
     */
    public function getRequestDecorators(): array
    {
        return $this->requestDecorators;
    }

}