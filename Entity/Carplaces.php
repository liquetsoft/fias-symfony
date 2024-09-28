<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Сведения по машино-местам.
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class Carplaces
{
    /**
     * Уникальный идентификатор записи. Ключевое поле.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $id = 0;

    /**
     * Глобальный уникальный идентификатор объекта типа INTEGER.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $objectid = 0;

    /**
     * Глобальный уникальный идентификатор адресного объекта типа UUID.
     *
     * @ORM\Column(type="uuid", nullable=false)
     */
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected ?Uuid $objectguid = null;

    /**
     * ID изменившей транзакции.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $changeid = 0;

    /**
     * Номер машиноместа.
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false, length: 50)]
    protected string $number = '';

    /**
     * Статус действия над записью – причина появления записи.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $opertypeid = 0;

    /**
     * Идентификатор записи связывания с предыдущей исторической записью.
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $previd = null;

    /**
     * Идентификатор записи связывания с последующей исторической записью.
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $nextid = null;

    /**
     * Дата внесения (обновления) записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $updatedate = null;

    /**
     * Начало действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $startdate = null;

    /**
     * Окончание действия записи.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $enddate = null;

    /**
     * Статус актуальности адресного объекта ФИАС.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $isactual = 0;

    /**
     * Признак действующего адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $isactive = 0;

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

    public function setObjectguid(Uuid $objectguid): self
    {
        $this->objectguid = $objectguid;

        return $this;
    }

    public function getObjectguid(): Uuid
    {
        if ($this->objectguid === null) {
            throw new \InvalidArgumentException("Parameter 'objectguid' isn't set.");
        }

        return $this->objectguid;
    }

    public function setChangeid(int $changeid): self
    {
        $this->changeid = $changeid;

        return $this;
    }

    public function getChangeid(): int
    {
        return $this->changeid;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setOpertypeid(int $opertypeid): self
    {
        $this->opertypeid = $opertypeid;

        return $this;
    }

    public function getOpertypeid(): int
    {
        return $this->opertypeid;
    }

    public function setPrevid(?int $previd): self
    {
        $this->previd = $previd;

        return $this;
    }

    public function getPrevid(): ?int
    {
        return $this->previd;
    }

    public function setNextid(?int $nextid): self
    {
        $this->nextid = $nextid;

        return $this;
    }

    public function getNextid(): ?int
    {
        return $this->nextid;
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

    public function setIsactual(int $isactual): self
    {
        $this->isactual = $isactual;

        return $this;
    }

    public function getIsactual(): int
    {
        return $this->isactual;
    }

    public function setIsactive(int $isactive): self
    {
        $this->isactive = $isactive;

        return $this;
    }

    public function getIsactive(): int
    {
        return $this->isactive;
    }
}
