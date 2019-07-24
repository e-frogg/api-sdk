<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 29/03/19
 * Time: 18:13
 */

namespace Apistarter\Sdk\Decorator;


class Utf8DecodeDecorator implements BodyDecoratorInterface
{

    public function decorate(string $body):string
    {
        return utf8_decode($body);
    }
}