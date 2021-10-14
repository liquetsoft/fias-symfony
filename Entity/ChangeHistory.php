<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

/**
 * Сведения по истории изменений.
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class ChangeHistory
{
    /**
     * ID изменившей транзакции.
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $changeid = 0;

    /**
     * Уникальный ID объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $objectid = 0;

    /**
     * Уникальный ID изменившей транзакции (GUID).
     *
     * @ORM\Column(type="uuid", nullable=false)
     */
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected ?Uuid $adrobjectid = null;

    /**
     * Тип операции.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $opertypeid = 0;

    /**
     * ID документа.
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    protected ?int $ndocid = null;

    /**
     * Дата изменения.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?DateTimeImmutable $changedate = null;

    public function setChangeid(int $changeid): self
    {
        $this->changeid = $changeid;

        return $this;
    }

    public function getChangeid(): int
    {
        return $this->changeid;
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

    public function setAdrobjectid(Uuid $adrobjectid): self
    {
        $this->adrobjectid = $adrobjectid;

        return $this;
    }

    public function getAdrobjectid(): Uuid
    {
        if ($this->adrobjectid === null) {
            throw new InvalidArgumentException("Parameter 'adrobjectid' isn't set.");
        }

        return $this->adrobjectid;
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

    public function setNdocid(?int $ndocid): self
    {
        $this->ndocid = $ndocid;

        return $this;
    }

    public function getNdocid(): ?int
    {
        return $this->ndocid;
    }

    public function setChangedate(DateTimeImmutable $changedate): self
    {
        $this->changedate = $changedate;

        return $this;
    }

    public function getChangedate(): DateTimeImmutable
    {
        if ($this->changedate === null) {
            throw new InvalidArgumentException("Parameter 'changedate' isn't set.");
        }

        return $this->changedate;
    }
}
