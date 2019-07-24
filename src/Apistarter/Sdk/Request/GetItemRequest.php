<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 06/03/19
 * Time: 12:42
 */

namespace Apistarter\Sdk\Request;


class GetItemRequest extends GetRequest
{

    protected static $uriVars = [
        '{resource_id}' => 'resource_id'
    ];

    public $resource_id;
    /** @noinspection MagicMethodsValidityInspection */


    /**
     * PostRequest constructor.
     * @param null $resource_id
     */
    public function __construct($resource_id = null)
    {
        $this->resource_id = $resource_id;
    }

}