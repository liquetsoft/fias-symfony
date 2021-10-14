<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\FiasSerializerObject;

/**
 * Тест для объекта, который сереализует данные из ФИАС.
 *
 * @internal
 */
class FiasSerializerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно разберет данные их xml в объект.
     */
    public function testDenormalize(): void
    {
        $id = $this->createFakeData()->numberBetween(100000, 900000);
        $objectGUID = $this->createFakeData()->uuid();
        $name = $this->createFakeData()->text();
        $updateDate = $this->createFakeData()->dateTime()->format('Y-m-d');
        $testDate = $this->createFakeData()->dateTime()->format('Y-m-d');
        $operTypeId = $this->createFakeData()->numberBetween(1, 100);
        $uuidString = $this->createFakeData()->uuid();
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

        $object = $serializer->deserialize($data, FiasSerializerObject::class, 'xml');

        $testObjectGUID = (string) $object->getObjectguid();

        $testUpdateDate = $object->getUpdatedate();
        $testUpdateDate = $testUpdateDate ? $testUpdateDate->format('Y-m-d') : null;

        $testTestDate = $object->getTestDate();
        $testTestDate = $testTestDate ? $testTestDate->format('Y-m-d') : null;

        $testUuid = (string) $object->getUuid();

        $this->assertInstanceOf(FiasSerializerObject::class, $object);
        $this->assertSame($id, $object->getId());
        $this->assertSame($name, $object->getName());
        $this->assertSame($operTypeId, $object->getOpertypeid());
        $this->assertSame($updateDate, $testUpdateDate);
        $this->assertSame($uuidString, $testUuid);
        $this->assertSame($objectGUID, $testObjectGUID);
        $this->assertNull($object->getNextid());
        $this->assertSame($testDate, $testTestDate);
    }
}
