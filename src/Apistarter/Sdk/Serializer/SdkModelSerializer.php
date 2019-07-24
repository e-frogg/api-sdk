<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 20/02/19
 * Time: 15:53
 */

namespace Apistarter\Sdk\Serializer;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class SdkModelSerializer
{
    public static function factory(): Serializer
    {
        $normalizers = [
            new ObjectCollectionNormalizer(),
            new SdkModelNormalizer(null, null, null, new SdkModelTypeExtractor()),
            new ArrayDenormalizer(),
            new DateTimeNormalizer(),
        ];

        return new Serializer($normalizers, [new XmlEncoder(), new JsonEncoder()]);

    }
}