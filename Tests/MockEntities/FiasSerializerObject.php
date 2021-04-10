<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

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

    public function setActstatid(int $ACTSTATID): void
    {
        $this->ACTSTATID = $ACTSTATID;
    }

    public function getActstatid()
    {
        return $this->ACTSTATID;
    }

    public function setName($NAME): void
    {
        $this->name = $NAME;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTestDate(DateTimeInterface $testDate): void
    {
        $this->testDate = $testDate;
    }

    public function getTestDate()
    {
        return $this->testDate;
    }

    public function setKodtst(string $kodtst): void
    {
        $this->kodtst = $kodtst;
    }

    public function getKodtst()
    {
        return $this->kodtst;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setEmptyStringInt(int $emptyStringInt): void
    {
        $this->emptyStringInt = $emptyStringInt;
    }

    public function getEmptyStringInt()
    {
        return $this->emptyStringInt;
    }
}
