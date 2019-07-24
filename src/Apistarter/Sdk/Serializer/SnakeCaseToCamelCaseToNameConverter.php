<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 25/02/19
 * Time: 21:37
 */

namespace Apistarter\Sdk\Serializer;


use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class SnakeCaseToCamelCaseToNameConverter extends CamelCaseToSnakeCaseNameConverter
{
    public function denormalize($propertyName)
    {
        return parent::normalize($propertyName);
    }
    public function normalize($propertyName)
    {
        return parent::denormalize($propertyName);
    }
}