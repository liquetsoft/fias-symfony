<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\Serializer\FiasSerializerFormat;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasUuidNormalizer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для объекта, который сериализует/десериализует uuid.
 *
 * @internal
 */
final class FiasUuidNormalizerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно нормализуется в строку.
     */
    public function testNormalize(): void
    {
        $uuidString = 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6';
        $uuid = Uuid::fromString($uuidString);

        $normalizer = new FiasUuidNormalizer();
        $res = $normalizer->normalize($uuid, FiasSerializerFormat::XML->value);

        $this->assertSame($uuidString, $res);
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке нормализовать
     * неверный объект.
     */
    public function testNormalizeWrongObjectException(): void
    {
        $normalizer = new FiasUuidNormalizer();

        $this->expectException(InvalidArgumentException::class);
        $normalizer->normalize('123', FiasSerializerFormat::XML->value);
    }

    /**
     * Проверяет, что объект верно определяет поддерживается указанный тип
     * данных для нормализации или нет.
     *
     * @dataProvider provideSupportsNormalization
     */
    public function testSupportsNormalization(mixed $item, string $format, bool $expected): void
    {
        $normalizer = new FiasUuidNormalizer();
        $res = $normalizer->supportsNormalization($item, $format);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsNormalization(): array
    {
        return [
            'uuid' => [
                Uuid::v6(),
                FiasSerializerFormat::XML->value,
                true,
            ],
            'uuid and json' => [
                Uuid::v6(),
                'json',
                true,
            ],
            'any object' => [
                new \stdClass(),
                FiasSerializerFormat::XML->value,
                false,
            ],
            'string' => [
                'string',
                FiasSerializerFormat::XML->value,
                false,
            ],
            'null' => [
                null,
                FiasSerializerFormat::XML->value,
                false,
            ],
        ];
    }

    /**
     * Проверяет, что объект верно денормализует строку в объект uuid.
     */
    public function testDenormalize(): void
    {
        $uuidString = 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6';

        $normalizer = new FiasUuidNormalizer();
        $uuid = $normalizer->denormalize($uuidString, 'test', FiasSerializerFormat::XML->value);

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame($uuidString, (string) $uuid);
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке денормализовать
     * пустую строку.
     */
    public function testDenormalizeEmptyStringException(): void
    {
        $normalizer = new FiasUuidNormalizer();

        $this->expectException(NotNormalizableValueException::class);
        $normalizer->denormalize('', 'test', FiasSerializerFormat::XML->value);
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке денормализовать
     * строку не в формате uuid.
     */
    public function testDenormalizeWrongStringException(): void
    {
        $normalizer = new FiasUuidNormalizer();

        $this->expectException(NotNormalizableValueException::class);
        $normalizer->denormalize('test', 'test', FiasSerializerFormat::XML->value);
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке денормализовать null.
     */
    public function testDenormalizeNullException(): void
    {
        $normalizer = new FiasUuidNormalizer();

        $this->expectException(NotNormalizableValueException::class);
        $normalizer->denormalize(null, 'test', FiasSerializerFormat::XML->value);
    }

    /**
     * Проверяет, что объект верно определяет поддерживается указанный тип
     * данных для денормализации или нет.
     *
     * @dataProvider provideSupportsDenormalization
     */
    public function testSupportsDenormalization(string $data, string $type, string $format, bool $expected): void
    {
        $normalizer = new FiasUuidNormalizer();
        $res = $normalizer->supportsDenormalization($data, $type, $format);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsDenormalization(): array
    {
        return [
            'uuid and xml' => [
                'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
                Uuid::class,
                FiasSerializerFormat::XML->value,
                true,
            ],
            'uuid and json' => [
                'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
                Uuid::class,
                'json',
                true,
            ],
            'any string with correct type and xml' => [
                'test',
                Uuid::class,
                FiasSerializerFormat::XML->value,
                true,
            ],
            'uuid with wrong type' => [
                'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
                \stdClass::class,
                FiasSerializerFormat::XML->value,
                false,
            ],
        ];
    }

    /**
     * Проверяет, что объект вернет корректный список поддерживаемых объектов.
     *
     * @dataProvider provideGetSupportedTypes
     */
    public function testGetSupportedTypes(?string $format, array $expected): void
    {
        $normalizer = new FiasUuidNormalizer();

        $res = $normalizer->getSupportedTypes($format);

        $this->assertSame($expected, $res);
    }

    public static function provideGetSupportedTypes(): array
    {
        return [
            'xml type' => [
                FiasSerializerFormat::XML->value,
                [
                    Uuid::class => true,
                ],
            ],
            'null type' => [
                null,
                [
                    Uuid::class => true,
                ],
            ],
            'other type' => [
                'json',
                [
                    Uuid::class => true,
                ],
            ],
        ];
    }
}
