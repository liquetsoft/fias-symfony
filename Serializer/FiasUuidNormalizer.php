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
final class FiasUuidNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if (!($data instanceof Uuid)) {
            throw new InvalidArgumentException('The object must implement the "' . Uuid::class . '"');
        }

        return (string) $data;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Uuid;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
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
    #[\Override]
    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = []): bool
    {
        return Uuid::class === $type || is_subclass_of($type, Uuid::class);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, bool|null>
     */
    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            Uuid::class => true,
        ];
    }
}
