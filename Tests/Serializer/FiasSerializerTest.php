<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use DateTime;
use DateTimeInterface;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer\FiasSerializer;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для объекта, который сереализует данные из ФИАС.
 */
class FiasSerializerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно разберет данные их xml в объект.
     */
    public function testDenormalize()
    {
        $uuidString = $this->createFakeData()->uuid;
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
        $serializer = new FiasSerializer;

        $object = $serializer->deserialize($data, FiasSerializerObject::class, 'xml');

        $this->assertInstanceOf(FiasSerializerObject::class, $object);
        $this->assertSame(2, $object->getActstatid());
        $this->assertSame('Не актуальный', $object->getName());
        $this->assertSame('10', $object->getKodtst());
        $this->assertEquals(new DateTime('2019-10-10T10:10:10.02'), $object->getTestDate());
        $this->assertInstanceOf(UuidInterface::class, $object->getUuid());
        $this->assertSame($uuidString, $object->getUuid()->toString());
        $this->assertSame(0, $object->getEmptyStringInt());
    }
}

/**
 * Мок для проверки сериализатора.
 */
class FiasSerializerObject
{
    private $ACTSTATID;
    private $name;
    private $testDate;
    private $kodtst;
    private $uuid;
    private $emptyStringInt = 0;

    public function setActstatid(int $ACTSTATID)
    {
        $this->ACTSTATID = $ACTSTATID;
    }

    public function getActstatid()
    {
        return $this->ACTSTATID;
    }

    public function setName($NAME)
    {
        $this->name = $NAME;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTestDate(DateTimeInterface $testDate)
    {
        $this->testDate = $testDate;
    }

    public function getTestDate()
    {
        return $this->testDate;
    }

    public function setKodtst(string $kodtst)
    {
        $this->kodtst = $kodtst;
    }

    public function getKodtst()
    {
        return $this->kodtst;
    }

    public function setUuid(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setEmptyStringInt(int $emptyStringInt)
    {
        $this->emptyStringInt = $emptyStringInt;
    }

    public function getEmptyStringInt()
    {
        return $this->emptyStringInt;
    }
}
