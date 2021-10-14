<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
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
     *
     * @var int
     */
    protected int $level = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=250, nullable=false)
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
     * Дата внесения (обновления) записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $updatedate = null;

    /**
     * Начало действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $startdate = null;

    /**
     * Окончание действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $enddate = null;

    /**
     * Признак действующего адресного объекта.
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
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
