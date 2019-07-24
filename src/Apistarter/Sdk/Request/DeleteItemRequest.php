<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 06/03/19
 * Time: 12:42
 */

namespace Apistarter\Sdk\Request;


class DeleteItemRequest extends DeleteRequest
{

    // -------- debut a configurer -------------
    //  protected static $responseClass = DeleteResponse::class;
    //  protected static $endpoint = "/delivery_addresses/{resource_id}";
    // -------- fin a configurer -------------

    protected static $uriVars = [
        '{resource_id}' => 'resource_id'
    ];

    public $resource_id;
    /** @noinspection MagicMethodsValidityInspection */
    /** @noinspection PhpMissingParentConstructorInspection */


    /**
     * PostRequest constructor.
     * @param null $resource_id
     */
    public function __construct($resource_id = null)
    {
        $this->resource_id = $resource_id;
    }

}