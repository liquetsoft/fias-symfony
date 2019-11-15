<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность для тестов с использованием doctrine.
 *
 * @ORM\Entity
 */
class MockEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $testId = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $testName = '';

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $startdate;

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

    public function setStartdate(DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): DateTimeInterface
    {
        return $this->startdate;
    }
}
