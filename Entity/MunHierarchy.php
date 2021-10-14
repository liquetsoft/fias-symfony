<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения по иерархии в муниципальном делении.
 *
 * @ORM\MappedSuperclass
 */
class MunHierarchy
{
    /**
     * Уникальный идентификатор записи. Ключевое поле.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * Глобальный уникальный идентификатор адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $objectid = 0;

    /**
     * Идентификатор родительского объекта.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected ?int $parentobjid = null;

    /**
     * ID изменившей транзакции.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $changeid = 0;

    /**
     * Код ОКТМО.
     *
     * @ORM\Column(type="string", length=11, nullable=true)
     *
     * @var string|null
     */
    protected ?string $oktmo = null;

    /**
     * Идентификатор записи связывания с предыдущей исторической записью.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected ?int $previd = null;

    /**
     * Идентификатор записи связывания с последующей исторической записью.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected ?int $nextid = null;

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
     * Признак действующего адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
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

    public function setParentobjid(?int $parentobjid): self
    {
        $this->parentobjid = $parentobjid;

        return $this;
    }

    public function getParentobjid(): ?int
    {
        return $this->parentobjid;
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

    public function setOktmo(?string $oktmo): self
    {
        $this->oktmo = $oktmo;

        return $this;
    }

    public function getOktmo(): ?string
    {
        return $this->oktmo;
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
