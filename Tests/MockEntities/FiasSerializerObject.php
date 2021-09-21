<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use DateTimeInterface;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObj;
use Ramsey\Uuid\UuidInterface;

/**
 * Мок для проверки сериализатора.
 */
class FiasSerializerObject extends AddrObj
{
    private ?UuidInterface $uuid = null;

    private ?DateTimeInterface $testDate = null;

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setTestdate(DateTimeInterface $testDate): void
    {
        $this->testDate = $testDate;
    }

    public function getTestdate(): ?DateTimeInterface
    {
        return $this->testDate;
    }
}
