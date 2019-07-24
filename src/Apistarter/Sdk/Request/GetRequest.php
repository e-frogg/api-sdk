<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 20:21
 */

namespace Apistarter\Sdk\Request;


abstract class GetRequest extends AbstractRequest
{
    /** @var string */
    protected static $method = self::METHOD_GET;

    protected static $queryParameters = ['fields'];

}