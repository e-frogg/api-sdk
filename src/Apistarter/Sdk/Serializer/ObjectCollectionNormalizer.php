<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/02/19
 * Time: 10:26
 */

namespace Apistarter\Sdk\Serializer;

use Apistarter\Sdk\Model\SdkModelCollection;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ObjectCollectionNormalizer implements DenormalizerInterface, SerializerAwareInterface, NormalizerInterface
{
    /**
     * @var SerializerInterface|DenormalizerInterface
     */
    private $serializer;

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (null === $this->serializer) {
            throw new BadMethodCallException('Please set a serializer before calling denormalize()!');
        }
        if (!is_iterable($data)) {
            throw new InvalidArgumentException('Data expected to be an array, ' . gettype($data) . ' given.');
        }

        /** @var SdkModelCollection $collection */
        $collection = new $class();
        $serializer = $this->serializer;

        foreach ($data as $key => $value) {
            $item_class = $collection->getItemClass($key, $value);
            $collection->add($serializer->denormalize($value, $item_class, $format, $context));
        }
        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null/*, array $context = []*/)
    {

        return is_subclass_of($type,SdkModelCollection::class)
            && is_iterable($data)
//            && $this->serializer->supportsDenormalization($data, substr($type, 0, -2), $format, $context)
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        if (!$serializer instanceof DenormalizerInterface) {
            throw new InvalidArgumentException('Expected a serializer that also implements DenormalizerInterface.');
        }

        $this->serializer = $serializer;
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param SdkModelCollection $object Object to normalize
     * @param string $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool
     *
     * @throws InvalidArgumentException   Occurs when the object given is not an attempted type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     * @throws ExceptionInterface         Occurs for all the other cases of errors
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$this->serializer instanceof NormalizerInterface) {
            throw new LogicException('Cannot normalize object because the injected serializer is not a normalizer');
        }

        $normalized = [];
        foreach ($object as $item) {
            $normalized[] = $this->serializer->normalize($item, $format);
        }
        return $normalized;
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof SdkModelCollection;
    }
}
