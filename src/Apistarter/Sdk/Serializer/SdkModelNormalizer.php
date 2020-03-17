<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 04:45
 */

namespace Apistarter\Sdk\Serializer;


use Apistarter\Sdk\Model\SdkModel;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SdkModelNormalizer extends ObjectNormalizer
{
    protected function getAttributes($object, $format = null, array $context) {
        $context["attributes"]="NO_CACHE !!!!";
        return parent::getAttributes($object, $format,$context);

    }

    protected function extractAttributes($object, string $format = null, array $context = [])
    {
        return $object->getAttributes();
    }

    public function supportsDenormalization($data, $type, string $format = null)
    {
        return is_subclass_of($type,SdkModel::class);
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof SdkModel;
    }

}