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
final class UuidNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!($object instanceof Uuid)) {
            throw new InvalidArgumentException('The object must implement the "' . Uuid::class . '"');
        }

        return (string) $object;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Uuid;
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        if ('' === $data || null === $data) {
            throw new NotNormalizableValueException(
                'The data is either an empty string or null, you should pass a string that can be parsed to uuid'
            );
        }

        try {
            $uuid = Uuid::fromString((string) $data);
        } catch (\Throwable $e) {
            throw new NotNormalizableValueException(
                message: 'Error while converting string to uuid',
                previous: $e
            );
        }

        return $uuid;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = []): bool
    {
        return Uuid::class === $type || is_subclass_of($type, Uuid::class);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, bool|null>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            Uuid::class => true,
        ];
    }
}
