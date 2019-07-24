<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 20:21
 */

namespace Apistarter\Sdk\Request;


/**
 * Class PostRequest
 * @package Apistarter\Sdk\Request
 *
 *
 */
abstract class PostRequest extends AbstractRequest implements RequestWithBody
{
    use RequestWithBodyTrait;
    // -------- debut a configurer -------------
    //    protected static $bodyParams=[];
    //    protected static $responseClass = MyResponse::class;
    //    protected static $endpoint='/xxx';
    //    protected static $queryParameters = [];
    //    protected static $uriVars = [];
    // -------- fin a configurer -------------


    /** @var string */
    protected static $method = self::METHOD_POST;

}