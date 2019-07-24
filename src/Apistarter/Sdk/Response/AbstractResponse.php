<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 18/02/19
 * Time: 21:29
 */

namespace Apistarter\Sdk\Response;



use Apistarter\Sdk\Model\SdkModel;

/**
 * Class AbstractCreateResponse
 * @package B2b\Api\Response\Core
 *
 * @property string $status
 * @method string getStatus
 * @method setStatus(string $status)
 *
 * @property SdkResponseError $error
 * @method SdkResponseError getError
 * @method setError(SdkResponseError $error)
 *
 */
class AbstractResponse extends SdkModel
{
    // -------- debut a configurer -------------
    protected static $responsePropertyTypes=[];
    // -------- fin a configurer -------------

    public static function getPropertiesTypes(): array
    {
        return array_merge(parent::getPropertiesTypes(),[
            'error' => SdkResponseError::class
        ],static::$responsePropertyTypes);
    }
}