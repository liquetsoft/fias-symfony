<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

/**
 * Сведения об адресном элементе в части его идентификаторов.
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class ReestrObjects
{
    /**
     * Уникальный идентификатор объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $objectid = 0;

    /**
     * Дата создания.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?DateTimeImmutable $createdate = null;

    /**
     * ID изменившей транзакции.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $changeid = 0;

    /**
     * Уровень объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $levelid = 0;

    /**
     * Дата обновления.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?DateTimeImmutable $updatedate = null;

    /**
     * GUID объекта.
     *
     * @ORM\Column(type="uuid", nullable=false)
     */
    #[ORM\Column(type: 'uuid', nullable: false)]
    protected ?Uuid $objectguid = null;

    /**
     * Признак действующего объекта (1 - действующий, 0 - не действующий).
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $isactive = 0;

    public function setObjectid(int $objectid): self
    {
        $this->objectid = $objectid;

        return $this;
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function setCreatedate(DateTimeImmutable $createdate): self
    {
        $this->createdate = $createdate;

        return $this;
    }

    public function getCreatedate(): DateTimeImmutable
    {
        if ($this->createdate === null) {
            throw new InvalidArgumentException("Parameter 'createdate' isn't set.");
        }

        return $this->createdate;
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

    public function setLevelid(int $levelid): self
    {
        $this->levelid = $levelid;

        return $this;
    }

    public function getLevelid(): int
    {
        return $this->levelid;
    }

    public function setUpdatedate(DateTimeImmutable $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getUpdatedate(): DateTimeImmutable
    {
        if ($this->updatedate === null) {
            throw new InvalidArgumentException("Parameter 'updatedate' isn't set.");
        }

        return $this->updatedate;
    }

    public function setObjectguid(Uuid $objectguid): self
    {
        $this->objectguid = $objectguid;

        return $this;
    }

    public function getObjectguid(): Uuid
    {
        if ($this->objectguid === null) {
            throw new InvalidArgumentException("Parameter 'objectguid' isn't set.");
        }

        return $this->objectguid;
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
