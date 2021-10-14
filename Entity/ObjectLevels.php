<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения по уровням адресных объектов.
 *
 * @ORM\MappedSuperclass
 */
class ObjectLevels
{
    /**
     * Уникальный идентификатор записи. Ключевое поле. Номер уровня объекта.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     */
    protected int $level = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    protected string $name = '';

    /**
     * Краткое наименование.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected ?string $shortname = null;

    /**
     * Дата внесения (обновления) записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    protected ?DateTimeImmutable $updatedate = null;

    /**
     * Начало действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    protected ?DateTimeImmutable $startdate = null;

    /**
     * Окончание действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    protected ?DateTimeImmutable $enddate = null;

    /**
     * Признак действующего адресного объекта.
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected string $isactive = '';

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
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

    public function setShortname(?string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setUpdatedate(DateTimeImmutable $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getUpdatedate(): ?DateTimeImmutable
    {
        return $this->updatedate;
    }

    public function setStartdate(DateTimeImmutable $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): ?DateTimeImmutable
    {
        return $this->startdate;
    }

    public function setEnddate(DateTimeImmutable $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getEnddate(): ?DateTimeImmutable
    {
        return $this->enddate;
    }

    public function setIsactive(string $isactive): self
    {
        $this->isactive = $isactive;

        return $this;
    }

    public function getIsactive(): string
    {
        return $this->isactive;
    }
}
