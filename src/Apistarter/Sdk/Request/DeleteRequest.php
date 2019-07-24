<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 20:21
 */

namespace Apistarter\Sdk\Request;


use Apistarter\Sdk\Response\DeleteResponse;

abstract class DeleteRequest extends AbstractRequest
{
    /** @var string */
    protected static $method = self::METHOD_DELETE;

    protected static $responseClass = DeleteResponse::class;
}