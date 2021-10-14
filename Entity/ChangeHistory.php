<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Сведения по истории изменений.
 *
 * @ORM\MappedSuperclass
 */
class ChangeHistory
{
    /**
     * ID изменившей транзакции.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $changeid = 0;

    /**
     * Уникальный ID объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $objectid = 0;

    /**
     * Уникальный ID изменившей транзакции (GUID).
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface|null
     */
    protected ?UuidInterface $adrobjectid = null;

    /**
     * Тип операции.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $opertypeid = 0;

    /**
     * ID документа.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected ?int $ndocid = null;

    /**
     * Дата изменения.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeImmutable|null
     */
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

    public function setAdrobjectid(UuidInterface $adrobjectid): self
    {
        $this->adrobjectid = $adrobjectid;

        return $this;
    }

    public function getAdrobjectid(): ?UuidInterface
    {
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

    public function getChangedate(): ?DateTimeImmutable
    {
        return $this->changedate;
    }
}
