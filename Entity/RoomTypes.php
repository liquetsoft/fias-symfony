<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
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
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $updatedate = null;

    /**
     * Начало действия записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $startdate = null;

    /**
     * Окончание действия записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $enddate = null;

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

    public function setUpdatedate(DateTimeInterface $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getUpdatedate(): ?DateTimeInterface
    {
        return $this->updatedate;
    }

    public function setStartdate(DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): ?DateTimeInterface
    {
        return $this->startdate;
    }

    public function setEnddate(DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getEnddate(): ?DateTimeInterface
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