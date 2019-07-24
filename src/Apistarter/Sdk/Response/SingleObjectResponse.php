<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 04/03/19
 * Time: 10:38
 */

namespace Apistarter\Sdk\Response;


use Apistarter\Sdk\Model\SdkModel;

abstract class SingleObjectResponse extends SdkModel
{

    // -------- debut a configurer -------------
    //    protected static $response_property_name;
    //    protected static $response_property_type;
    // -------- fin a configurer -------------

    /**
     * nom de la propriété qui sera désérialisée
     * @var
     */
    protected static $response_property_name;

    /**
     * classe de la propriété qui sera désérialisée
     * @var
     */
    protected static $response_property_type;

    /**
     * @return mixed
     */
    public static function getResponsePropertyType()
    {
        return static::$response_property_type;
    }

    /**
     * @return mixed
     */
    public static function getResponsePropertyName()
    {
        return static::$response_property_name;
    }


}