<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\UuidNormalizer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для объекта, который сериализует/десериализует uuid.
 *
 * @internal
 */
final class UuidNormalizerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно нормализуется в строку.
     */
    public function testNormalize(): void
    {
        $uuidString = 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6';
        $uuid = Uuid::fromString($uuidString);

        $normalizer = new UuidNormalizer();

        $this->assertSame($uuidString, $normalizer->normalize($uuid));
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке нормализовать
     * неверный объект.
     */
    public function testNormalizeWrongObjectException(): void
    {
        $normalizer = new UuidNormalizer();

        $this->expectException(InvalidArgumentException::class);
        $normalizer->normalize('123');
    }

    /**
     * Проверяет, что объект верно определяет поддерживается указанный тип
     * данных для нормализации или нет.
     *
     * @dataProvider provideSupportsNormalization
     */
    public function testSupportsNormalization(mixed $item, bool $expected): void
    {
        $normalizer = new UuidNormalizer();
        $res = $normalizer->supportsNormalization($item);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsNormalization(): array
    {
        return [
            'uuid' => [
                Uuid::v6(),
                true,
            ],
            'any object' => [
                new \stdClass(),
                false,
            ],
            'string' => [
                'string',
                false,
            ],
            'null' => [
                null,
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

        $normalizer = new UuidNormalizer();
        $uuid = $normalizer->denormalize($uuidString, 'test');

        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame($uuidString, (string) $uuid);
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке денормализовать
     * пустую строку.
     */
    public function testDenormalizeEmptyStringException(): void
    {
        $normalizer = new UuidNormalizer();

        $this->expectException(NotNormalizableValueException::class);
        $normalizer->denormalize('', 'test');
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке денормализовать
     * строку не в формате uuid.
     */
    public function testDenormalizeWrongStringException(): void
    {
        $normalizer = new UuidNormalizer();

        $this->expectException(NotNormalizableValueException::class);
        $normalizer->denormalize('test', 'test');
    }

    /**
     * Проверяет, что объект выбросит исключение при попытке денормализовать null.
     */
    public function testDenormalizeNullException(): void
    {
        $normalizer = new UuidNormalizer();

        $this->expectException(NotNormalizableValueException::class);
        $normalizer->denormalize(null, 'test');
    }

    /**
     * Проверяет, что объект верно определяет поддерживается указанный тип
     * данных для денормализации или нет.
     *
     * @dataProvider provideSupportsDenormalization
     */
    public function testSupportsDenormalization(string $data, string $type, bool $expected): void
    {
        $normalizer = new UuidNormalizer();
        $res = $normalizer->supportsDenormalization($data, $type);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsDenormalization(): array
    {
        return [
            'uuid' => [
                'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
                Uuid::class,
                true,
            ],
            'any string with correct type' => [
                'test',
                Uuid::class,
                true,
            ],
            'uuid with wrong type' => [
                'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
                \stdClass::class,
                false,
            ],
        ];
    }

    /**
     * Проверяет, что объект вернет корректный список поддерживаемых объектов.
     */
    public function testGetSupportedTypes(): void
    {
        $normalizer = new UuidNormalizer();

        $res = $normalizer->getSupportedTypes(null);

        $this->assertSame(
            [
                Uuid::class => true,
            ],
            $res
        );
    }
}
