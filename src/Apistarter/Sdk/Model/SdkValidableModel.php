<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 13/03/19
 * Time: 14:38
 */

namespace Apistarter\Sdk\Model;


class SdkValidableModel extends SdkModel
{
    protected static $validableFields = array();

    public static function getValidableFields(){
        return static::$validableFields;
    }

}