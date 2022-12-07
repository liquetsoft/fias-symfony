<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObj;
use Symfony\Component\Uid\Uuid;

/**
 * Мок для проверки сериализатора.
 */
class FiasSerializerObject extends AddrObj
{
    private ?Uuid $uuid = null;

    private ?\DateTimeInterface $testDate = null;

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setTestdate(\DateTimeInterface $testDate): void
    {
        $this->testDate = $testDate;
    }

    public function getTestdate(): ?\DateTimeInterface
    {
        return $this->testDate;
    }
}
