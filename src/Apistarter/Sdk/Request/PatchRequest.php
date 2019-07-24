<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 20:21
 */

namespace Apistarter\Sdk\Request;


use LogicException;

abstract class PatchRequest extends GetItemRequest implements RequestWithBody
{
    use RequestWithBodyTrait;

    /** @var string */
    protected static $method = self::METHOD_PATCH;
    // -------- debut a configurer -------------
    //    protected static $responseClass = MyResponse::class;
    //    protected static $endpoint='/xxx';
    // -------- fin a configurer -------------
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * PostRequest constructor.
     * @param $resource_body
     * @param null $resourceId
     */
    public function __construct($resource_body = null,$resourceId = null)
    {
        $this->resource_body = $resource_body;
        if(null === $resourceId) {
            $this->resource_id = $this->guessResourceId();
        } else {
            $this->resource_id = $resourceId;
        }
    }
    /**
     * @return mixed
     */
    public function guessResourceId()
    {
        if (is_object($this->resource_body)) {
            if (method_exists($this->resource_body, "getId")) {
                return $this->resource_body->getId();
            }
            if (isset($this->resource_body->id)) {
                return $this->resource_body->id;
            }
        } elseif (is_array($this->resource_body) && isset($this->resource_body['id'])) {
            return $this->resource_body['id'];
        }
        throw new LogicException("update a resource must have getId or override this guessResourceId method");
    }


}