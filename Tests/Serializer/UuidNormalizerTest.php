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
class UuidNormalizerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно нормализуется в строку.
     */
    public function testNormalize(): void
    {
        $uuidString = $this->createFakeData()->uuid();
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
     */
    public function testSupportsNormalization(): void
    {
        $uuid = Uuid::fromString($this->createFakeData()->uuid());

        $normalizer = new UuidNormalizer();

        $this->assertTrue($normalizer->supportsNormalization($uuid));
        $this->assertFalse($normalizer->supportsNormalization(new \stdClass()));
        $this->assertFalse($normalizer->supportsNormalization('123'));
        $this->assertFalse($normalizer->supportsNormalization(null));
        $this->assertFalse($normalizer->supportsNormalization(false));
    }

    /**
     * Проверяет, что объект верно денормализует строку в объект uuid.
     */
    public function testDenormalize(): void
    {
        $uuidString = $this->createFakeData()->uuid();

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
     * Проверяет, что объект верно определяет поддерживается указанный тип
     * данных для денормализации или нет.
     */
    public function testSupportsDenormalization(): void
    {
        $normalizer = new UuidNormalizer();
        $uuidString = $this->createFakeData()->uuid();

        $this->assertTrue($normalizer->supportsDenormalization('test', Uuid::class));
        $this->assertTrue($normalizer->supportsDenormalization($uuidString, Uuid::class));
        $this->assertFalse($normalizer->supportsDenormalization($uuidString, \stdClass::class));
        $this->assertFalse($normalizer->supportsDenormalization($uuidString, 'test'));
    }
}
