<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\Uuid;

/**
 * Нормализатор для объектов uuid.
 */
class UuidNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!($object instanceof Uuid)) {
            throw new InvalidArgumentException('The object must implement the "' . Uuid::class . '".');
        }

        return (string) $object;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Uuid;
    }

    /**
     * {@inheritDoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if ('' === $data || null === $data) {
            throw new NotNormalizableValueException(
                'The data is either an empty string or null, you should pass a string that can be parsed to uuid.'
            );
        }

        try {
            $uuid = Uuid::fromString($data);
        } catch (\Throwable $e) {
            throw new NotNormalizableValueException(
                'Error while converting string to uuid.',
                0,
                $e
            );
        }

        return $uuid;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return Uuid::class === $type || is_subclass_of($type, Uuid::class);
    }
}
