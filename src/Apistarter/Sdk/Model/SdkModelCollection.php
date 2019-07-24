<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 10:28
 */

namespace Apistarter\Sdk\Model;


use Efrogg\Collection\ObjectCollection;

class SdkModelCollection extends ObjectCollection
{
    // -------- debut a configurer -------------
    //    protected static $itemClass;
    // -------- fin a configurer -------------

    protected static $itemClass;

    public function getItemClass() {
        return static::$itemClass;
    }

}