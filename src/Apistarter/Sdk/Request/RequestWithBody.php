<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 05/03/19
 * Time: 23:15
 */

namespace Apistarter\Sdk\Request;


interface RequestWithBody
{
    /**
     * @return array|mixed|null
     */
    public function getBodyParams();

    public function getBodyParameterNames();

}