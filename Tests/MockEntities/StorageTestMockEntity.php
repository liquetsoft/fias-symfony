<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность для тестов хранилища с использованием doctrine.
 */
#[ORM\Entity]
class StorageTestMockEntity
{
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    private int $testId = 0;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $testName = '';

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTimeInterface $startdate = null;

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
}
