<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Сведения по типам нормативных документов.
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class NormativeDocsTypes
{
    /**
     * Идентификатор записи.
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $id = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false, length: 500)]
    protected string $name = '';

    /**
     * Дата начала действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?DateTimeImmutable $startdate = null;

    /**
     * Дата окончания действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?DateTimeImmutable $enddate = null;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setStartdate(DateTimeImmutable $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): DateTimeImmutable
    {
        if ($this->startdate === null) {
            throw new InvalidArgumentException("Parameter 'startdate' isn't set.");
        }

        return $this->startdate;
    }

    public function setEnddate(DateTimeImmutable $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getEnddate(): DateTimeImmutable
    {
        if ($this->enddate === null) {
            throw new InvalidArgumentException("Parameter 'enddate' isn't set.");
        }

        return $this->enddate;
    }
}
