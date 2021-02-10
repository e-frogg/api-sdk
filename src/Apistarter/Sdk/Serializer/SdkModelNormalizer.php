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
    /**
     * @param SdkModel $object
     * @param null   $format
     * @param array  $context
     * @return string[]
     * @throws \Exception
     */
    protected function getAttributes($object, $format = null, array $context) {
        if($object->isStaticStructure() ) {
            $context['cache_key']=get_class($object);
        } else {
            $context['cache_key']=random_int(0,1000);
        }
        return parent::getAttributes($object, $format,$context);

    }

    /**
     * @param SdkModel      $object
     * @param string|null $format
     * @param array       $context
     * @return array|string[]
     */
    protected function extractAttributes($object, string $format = null, array $context = [])
    {
        if($object->isStaticStructure()) {
            return $object->getStaticStructure();
        }
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