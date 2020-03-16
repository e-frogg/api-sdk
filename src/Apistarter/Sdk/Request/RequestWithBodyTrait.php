<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 06/03/19
 * Time: 12:37
 */

namespace Apistarter\Sdk\Request;


use function is_array;

trait RequestWithBodyTrait
{
    /** @var array | string */
    protected static $bodyParams='resource_body';

    /**
     * @var mixed
     */
    protected $resource_body;

    /**
     * PostRequest constructor.
     * @param $request_body
     */
    public function __construct($request_body = null)
    {
        $this->resource_body = $request_body;
    }


    /**
     * @return array|mixed|null
     */
    public function getBodyParams()
    {
        if(is_array(static::$bodyParams)) {
            return array_combine(static::$bodyParams,array_map([$this,"__get"],static::$bodyParams));
        }

        if(is_string(static::$bodyParams)) {
            return $this->{static::$bodyParams};
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getResourceBody()
    {
        return $this->resource_body;
    }

    /**
     * @param mixed $resource_body
     * @return static
     */
    public function setResourceBody($resource_body)
    {
        $this->resource_body = $resource_body;
        return $this;
    }
}