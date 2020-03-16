<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 29/03/19
 * Time: 18:13
 */

namespace Apistarter\Sdk\Decorator;


class Utf8EncodeDecorator implements BodyDecoratorInterface
{

    public function decorate($body)
    {
        return utf8_encode($body);
    }
}