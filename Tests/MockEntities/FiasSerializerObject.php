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
    /**
     * @var int
     */
    private $ACTSTATID = 0;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var DateTimeInterface|null
     */
    private $testDate;

    /**
     * @var string
     */
    private $kodtst = '';

    /**
     * @var UuidInterface|null
     */
    private $uuid;

    /**
     * @var int
     */
    private $emptyStringInt = 0;

    public function setActstatid(int $ACTSTATID): void
    {
        $this->ACTSTATID = $ACTSTATID;
    }

    public function getActstatid(): int
    {
        return $this->ACTSTATID;
    }

    public function setName(string $NAME): void
    {
        $this->name = $NAME;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTestDate(DateTimeInterface $testDate): void
    {
        $this->testDate = $testDate;
    }

    public function getTestDate(): ?DateTimeInterface
    {
        return $this->testDate;
    }

    public function setKodtst(string $kodtst): void
    {
        $this->kodtst = $kodtst;
    }

    public function getKodtst(): string
    {
        return $this->kodtst;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setEmptyStringInt(int $emptyStringInt): void
    {
        $this->emptyStringInt = $emptyStringInt;
    }

    public function getEmptyStringInt(): int
    {
        return $this->emptyStringInt;
    }
}
