<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\Serializer\FiasSerializerFormat;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\FiasSerializerObject;

/**
 * Тест для объекта, который сереализует данные из ФИАС.
 *
 * @internal
 */
final class FiasSerializerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно разберет данные из xml в объект.
     */
    public function testDenormalize(): void
    {
        $id = 123321;
        $objectGUID = 'f81d4fae-7dec-11d0-a765-00a0c91e6bf6';
        $name = 'test name';
        $updateDate = '2024-10-10';
        $testDate = '2024-11-11';
        $operTypeId = 321;
        $uuidString = '550e8400-e29b-41d4-a716-446655440000';

        $data = <<<EOT
<OBJECT
    ID="{$id}"
    OBJECTGUID="{$objectGUID}"
    NEXTID=""
    NAME="{$name}"
    OPERTYPEID="{$operTypeId}"
    UPDATEDATE="{$updateDate}"
    uuid="{$uuidString}"
    testDate="{$testDate}"
/>
EOT;

        $serializer = new FiasSerializer();
        $object = $serializer->deserialize($data, FiasSerializerObject::class, FiasSerializerFormat::XML->value);

        $this->assertInstanceOf(FiasSerializerObject::class, $object);
        $this->assertSame($id, $object->getId());
        $this->assertSame($name, $object->getName());
        $this->assertSame($operTypeId, $object->getOpertypeid());
        $this->assertSame($updateDate, $object->getUpdatedate()->format('Y-m-d'));
        $this->assertSame($uuidString, (string) $object->getUuid());
        $this->assertSame($objectGUID, (string) $object->getObjectguid());
        $this->assertNull($object->getNextid());
        $this->assertSame($testDate, $object->getTestDate()?->format('Y-m-d'));
    }
}
