<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 29/03/19
 * Time: 18:11
 */

namespace Apistarter\Sdk\Decorator;

interface BodyDecoratorInterface
{
    public function decorate($body);
}