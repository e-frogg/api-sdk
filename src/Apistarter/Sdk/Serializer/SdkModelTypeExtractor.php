<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 04:10
 */

namespace Apistarter\Sdk\Serializer;


use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

class SdkModelTypeExtractor implements PropertyTypeExtractorInterface
{

    /**
     * Gets types of a property.
     *
     * @param string $class
     * @param string $property
     * @param array $context
     *
     * @return Type[]|null
     */
    public function getTypes($class, $property, array $context = [])
    {
        static $cacheProperties=[];

        // cache des propriétés custom de classe
        if(!isset($cacheProperties[$class]) && !array_keys($cacheProperties,$class)) {
            $cacheProperties[$class]=null;
            if(method_exists($class,"getPropertiesTypes")) {
                $cacheProperties[$class] = $class::getPropertiesTypes();
            }
        }

        // on a une prop custom
        if (isset($cacheProperties[$class][$property])) {
            return [
                new Type(Type::BUILTIN_TYPE_OBJECT, true, $cacheProperties[$class][$property])
            ];

        }

        return null;
    }
}