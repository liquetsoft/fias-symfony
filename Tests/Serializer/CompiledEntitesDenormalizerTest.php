<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use App\Entity\AddrObj;
use Liquetsoft\Fias\Component\Serializer\FiasSerializerFormat;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\CompiledEntitesDenormalizer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\FiasSerializerObject;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * Тест для объекта, который сереализует данные из ФИАС.
 *
 * @internal
 */
final class CompiledEntitesDenormalizerTest extends BaseCase
{
    /**
     * Проверяет, что денормалайзер правильно определит, что может преобразовать тип.
     *
     * @dataProvider provideSupportsDenormalization
     */
    public function testSupportsDenormalization(string $type, string $format, bool $expected): void
    {
        $denormalizer = new CompiledEntitesDenormalizer();
        $res = $denormalizer->supportsDenormalization([], $type, $format);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsDenormalization(): array
    {
        return [
            'supported type and format' => [
                FiasSerializerObject::class,
                FiasSerializerFormat::XML->value,
                true,
            ],
            'unsupported type' => [
                'test',
                FiasSerializerFormat::XML->value,
                false,
            ],
            'unsupported format' => [
                FiasSerializerObject::class,
                'json',
                false,
            ],
        ];
    }

    /**
     * Проверяет, что объект правильно разберет данные из xml в объект.
     */
    public function testDenormalize(): void
    {
        $id = 123321;
        $objectGUID = 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6';
        $name = 'test name';
        $updateDate = '2024-10-10';
        $operTypeId = 321;

        $data = [
            '@ID' => $id,
            '@OBJECTGUID' => $objectGUID,
            '@NEXTID' => '',
            '@NAME' => $name,
            '@OPERTYPEID' => $operTypeId,
            '@UPDATEDATE' => $updateDate,
        ];

        $serializer = new CompiledEntitesDenormalizer();
        $object = $serializer->denormalize($data, FiasSerializerObject::class, FiasSerializerFormat::XML->value);

        $this->assertInstanceOf(FiasSerializerObject::class, $object);
        $this->assertSame($id, $object->getId());
        $this->assertSame($name, $object->getName());
        $this->assertSame($operTypeId, $object->getOpertypeid());
        $this->assertSame($updateDate, $object->getUpdatedate()->format('Y-m-d'));
        $this->assertSame($objectGUID, (string) $object->getObjectguid());
        $this->assertNull($object->getNextid());
    }

    /**
     * Проверяет, что денормалайзер не будет обрабатывать данные, если предоставлен не массив.
     */
    public function testDenormalizeNotAnArrayException(): void
    {
        $denormalizer = new CompiledEntitesDenormalizer();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Array instance is required');
        $denormalizer->denormalize(123, AddrObj::class, FiasSerializerFormat::XML->value);
    }

    /**
     * Проверяет, что денормалайзер не будет обрабатывать данные, если указан неправильный тип.
     */
    public function testDenormalizeWrongTypeException(): void
    {
        $denormalizer = new CompiledEntitesDenormalizer();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't find data extractor");
        $denormalizer->denormalize([], \stdClass::class, FiasSerializerFormat::XML->value);
    }

    /**
     * Проверяет, что денормалайзер вернет верный список поддерживаемых объектов.
     *
     * @dataProvider provideGetSupportedTypes
     */
    public function testGetSupportedTypes(?string $format, array|true $expected): void
    {
        $denormalizer = new CompiledEntitesDenormalizer();
        $res = $denormalizer->getSupportedTypes($format);

        if ($expected === true) {
            $this->assertNotEmpty($res);
        } else {
            $this->assertSame($expected, $res);
        }
    }

    public static function provideGetSupportedTypes(): array
    {
        return [
            'xml format' => [
                FiasSerializerFormat::XML->value,
                true,
            ],
            'non xml format' => [
                'json',
                [],
            ],
        ];
    }
}
