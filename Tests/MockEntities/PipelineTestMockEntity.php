<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Сущность для тестов пайплайнов с использованием doctrine.
 */
#[ORM\Entity]
final class PipelineTestMockEntity
{
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    private int $testId = 0;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $testName = '';

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $startdate = null;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?Uuid $uuid = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $stringCode = null;

    public function setTestId(int $testId): self
    {
        $this->testId = $testId;

        return $this;
    }

    public function getTestId(): int
    {
        return $this->testId;
    }

    public function setTestName(string $testName): self
    {
        $this->testName = $testName;

        return $this;
    }

    public function getTestName(): string
    {
        return $this->testName;
    }

    public function setStartdate(?\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setUuid(?Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setStringCode(?string $stringCode): self
    {
        $this->stringCode = $stringCode;

        return $this;
    }

    public function getStringCode(): ?string
    {
        return $this->stringCode;
    }
}
