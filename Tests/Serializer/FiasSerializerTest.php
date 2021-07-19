<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\FiasSerializerObject;
use Ramsey\Uuid\UuidInterface;

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
        $uuidString = $this->createFakeData()->uuid();
        $data = <<<EOT
<ActualStatus
    ACTSTATID="2"
    NAME="&#x41D;&#x435; &#x430;&#x43A;&#x442;&#x443;&#x430;&#x43B;&#x44C;&#x43D;&#x44B;&#x439;"
    TESTDATE="2019-10-10T10:10:10.02"
    KOD_T_ST="10"
    uuid="{$uuidString}"
    EMPTYSTRINGINT=""
/>
EOT;
        $serializer = new FiasSerializer();

        $object = $serializer->deserialize($data, FiasSerializerObject::class, 'xml');

        $date = $object->getTestDate();
        $date = $date ? $date->format('Y-m-d H:i:s') : null;

        $uuid = $object->getUuid();
        $uuid = $uuid ? $uuid->toString() : null;

        $this->assertInstanceOf(FiasSerializerObject::class, $object);
        $this->assertSame(2, $object->getActstatid());
        $this->assertSame('Не актуальный', $object->getName());
        $this->assertSame('10', $object->getKodtst());
        $this->assertSame('2019-10-10 10:10:10', $date);
        $this->assertInstanceOf(UuidInterface::class, $object->getUuid());
        $this->assertSame($uuidString, $uuid);
        $this->assertSame(0, $object->getEmptyStringInt());
    }
}
