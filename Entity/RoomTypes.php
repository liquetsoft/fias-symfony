<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения по типам комнат.
 *
 * @ORM\MappedSuperclass
 */
class RoomTypes
{
    /**
     * Идентификатор типа (ключ).
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Краткое наименование.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string|null
     */
    protected ?string $shortname = null;

    /**
     * Описание.
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     *
     * @var string|null
     */
    protected ?string $desc = null;

    /**
     * Дата внесения (обновления) записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $updatedate = null;

    /**
     * Начало действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $startdate = null;

    /**
     * Окончание действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $enddate = null;

    /**
     * Статус активности.
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected string $isactive = '';

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

    public function setShortname(?string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setDesc(?string $desc): self
    {
        $this->desc = $desc;

        return $this;
    }

    public function getDesc(): ?string
    {
        return $this->desc;
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
