<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 03:27
 */

namespace Apistarter\Sdk\Model;


use Efrogg\Collection\ObjectArrayAccess;

class SdkModel extends ObjectArrayAccess
{
    // -------- debut a configurer -------------
    //    protected static $propertiesTypes = [];
    //    protected static $hidden = [];
    //    protected static $visible = [];
    // -------- fin a configurer -------------

    protected static $propertiesTypes = [];

    /**
     * @var array
     * @deprecated
     * cette propriété n'est pas encore implémentée
     */
    protected static $hidden = [];

    /**
     * @var array
     * @deprecated
     * cette propriété n'est pas encore implémentée
     */
    protected static $visible = [];


    // configuration ObjectArrayAccess
    protected static $property_case = self::CAMEL_CASE;
    protected static $strict_property_case = true;

    /**
     * @return array
     */
    public static function getPropertiesTypes():array {
        return static::$propertiesTypes;
    }

    /**
     * @deprecated
     * @return array
     * non utilisé
     */
    public static function getHiddenApiProperties():array {
        return static::$hidden;
    }
}