<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/03/19
 * Time: 13:13
 */

namespace Apistarter\Sdk\Exception;


use Efrogg\Collection\ObjectArrayAccess;

class SdkErrorData extends ObjectArrayAccess
{
    public $uuid;
    public $code_error;
    public $message;
    public $customData=[];
}