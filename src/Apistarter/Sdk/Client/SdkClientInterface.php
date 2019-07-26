<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 25/03/19
 * Time: 05:05
 */

namespace Apistarter\Sdk\Client;


use Apistarter\Sdk\Request\SdkRequestInterface;

interface SdkClientInterface
{
    public function execute(SdkRequestInterface $request);
}