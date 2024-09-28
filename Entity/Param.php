<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения о классификаторе параметров адресообразующих элементов и объектов недвижимости.
 *
 * @psalm-consistent-constructor
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class Param
{
    /**
     * Идентификатор записи.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $id = 0;

    /**
     * Глобальный уникальный идентификатор адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $objectid = 0;

    /**
     * ID изменившей транзакции.
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $changeid = null;

    /**
     * ID завершившей транзакции.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $changeidend = 0;

    /**
     * Тип параметра.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $typeid = 0;

    /**
     * Значение параметра.
     *
     * @ORM\Column(type="string", length=8000, nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false, length: 8000)]
    protected string $value = '';

    /**
     * Дата внесения (обновления) записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $updatedate = null;

    /**
     * Дата начала действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $startdate = null;

    /**
     * Дата окончания действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $enddate = null;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setObjectid(int $objectid): self
    {
        $this->objectid = $objectid;

        return $this;
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function setChangeid(?int $changeid): self
    {
        $this->changeid = $changeid;

        return $this;
    }

    public function getChangeid(): ?int
    {
        return $this->changeid;
    }

    public function setChangeidend(int $changeidend): self
    {
        $this->changeidend = $changeidend;

        return $this;
    }

    public function getChangeidend(): int
    {
        return $this->changeidend;
    }

    public function setTypeid(int $typeid): self
    {
        $this->typeid = $typeid;

        return $this;
    }

    public function getTypeid(): int
    {
        return $this->typeid;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setUpdatedate(\DateTimeImmutable $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getUpdatedate(): \DateTimeImmutable
    {
        if ($this->updatedate === null) {
            throw new \InvalidArgumentException("Parameter 'updatedate' isn't set.");
        }

        return $this->updatedate;
    }

    public function setStartdate(\DateTimeImmutable $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): \DateTimeImmutable
    {
        if ($this->startdate === null) {
            throw new \InvalidArgumentException("Parameter 'startdate' isn't set.");
        }

        return $this->startdate;
    }

    public function setEnddate(\DateTimeImmutable $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getEnddate(): \DateTimeImmutable
    {
        if ($this->enddate === null) {
            throw new \InvalidArgumentException("Parameter 'enddate' isn't set.");
        }

        return $this->enddate;
    }
}
