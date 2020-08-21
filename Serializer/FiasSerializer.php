<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer;

use Liquetsoft\Fias\Component\Serializer\FiasNameConverter;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Преднастроенный объект сериализатора для ФИАС.
 */
class FiasSerializer extends \Liquetsoft\Fias\Component\Serializer\FiasSerializer
{
    public function __construct(?array $normalizers = null, ?array $encoders = null)
    {
        if ($normalizers === null) {
            $normalizers = [
                new UuidNormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(
                    null,
                    new FiasNameConverter(),
                    null,
                    new ReflectionExtractor(),
                    null,
                    null,
                    [
                        ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                    ]
                ),
            ];
        }

        parent::__construct($normalizers, $encoders);
    }
}
