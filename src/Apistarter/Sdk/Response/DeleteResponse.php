<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 22/03/19
 * Time: 16:48
 */

namespace Apistarter\Sdk\Response;


use Apistarter\Sdk\Model\SdkModel;

/**
 * Class DeleteResponse
 * @package Apistarter\Sdk\Response
 *
 * @property int $code
 */
class DeleteResponse extends SdkModel
{
    public function isValid()
    {
        return $this->code === 204;
    }
}