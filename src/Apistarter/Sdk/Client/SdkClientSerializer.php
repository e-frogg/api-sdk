<?php


namespace Apistarter\Sdk\Client;


use Apistarter\Sdk\Serializer\ObjectCollectionNormalizer;
use Apistarter\Sdk\Serializer\SdkModelNormalizer;
use Apistarter\Sdk\Serializer\SdkModelTypeExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SdkClientSerializer extends Serializer
{
    public function __construct()
    {
        $normalizers = [
            new ObjectCollectionNormalizer(),
            new SdkModelNormalizer(null, null, null, new SdkModelTypeExtractor()),
            new ArrayDenormalizer(),
            new DateTimeNormalizer(),
            new ObjectNormalizer()
        ];

        parent::__construct($normalizers, [new XmlEncoder(), new JsonEncoder()]);

    }
}