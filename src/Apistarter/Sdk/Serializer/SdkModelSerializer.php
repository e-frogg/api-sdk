<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 20/02/19
 * Time: 15:53
 */

namespace Apistarter\Sdk\Serializer;


use Symfony\Component\Serializer\Encoder\ContextAwareDecoderInterface;
use Symfony\Component\Serializer\Encoder\ContextAwareEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SdkModelSerializer implements SerializerInterface, ContextAwareNormalizerInterface, ContextAwareDenormalizerInterface, ContextAwareEncoderInterface,
                                    ContextAwareDecoderInterface
{
    private Serializer $serializer;

    public function __construct()
    {
        $this->serializer = self::factory();
    }

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

    public function supportsDecoding(string $format, array $context = [])
    {
        return $this->serializer->supportsDecoding(...func_get_args());
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $this->serializer->supportsDenormalization(...func_get_args());
    }

    public function supportsEncoding(string $format, array $context = [])
    {
        return $this->serializer->supportsEncoding(...func_get_args());
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $this->serializer->supportsNormalization(...func_get_args());
    }

    public function decode(string $data, string $format, array $context = [])
    {
        return $this->serializer->decode(...func_get_args());
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $this->serializer->denormalize(...func_get_args());
    }

    public function encode($data, string $format, array $context = [])
    {
        return $this->serializer->encode(...func_get_args());
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        return $this->serializer->normalize(...func_get_args());
    }

    public function serialize($data, string $format, array $context = [])
    {
        return $this->serializer->serialize(...func_get_args());
    }

    public function deserialize($data, string $type, string $format, array $context = [])
    {
        return $this->serializer->deserialize(...func_get_args());
    }

}
