<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения по иерархии в административном делении.
 *
 * @ORM\MappedSuperclass
 */
class AdmHierarchy
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
     * Глобальный уникальный идентификатор объекта.
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
     * Код региона.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected ?string $regioncode = null;

    /**
     * Код района.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected ?string $areacode = null;

    /**
     * Код города.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected ?string $citycode = null;

    /**
     * Код населенного пункта.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected ?string $placecode = null;

    /**
     * Код ЭПС.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected ?string $plancode = null;

    /**
     * Код улицы.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected ?string $streetcode = null;

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

    public function setRegioncode(?string $regioncode): self
    {
        $this->regioncode = $regioncode;

        return $this;
    }

    public function getRegioncode(): ?string
    {
        return $this->regioncode;
    }

    public function setAreacode(?string $areacode): self
    {
        $this->areacode = $areacode;

        return $this;
    }

    public function getAreacode(): ?string
    {
        return $this->areacode;
    }

    public function setCitycode(?string $citycode): self
    {
        $this->citycode = $citycode;

        return $this;
    }

    public function getCitycode(): ?string
    {
        return $this->citycode;
    }

    public function setPlacecode(?string $placecode): self
    {
        $this->placecode = $placecode;

        return $this;
    }

    public function getPlacecode(): ?string
    {
        return $this->placecode;
    }

    public function setPlancode(?string $plancode): self
    {
        $this->plancode = $plancode;

        return $this;
    }

    public function getPlancode(): ?string
    {
        return $this->plancode;
    }

    public function setStreetcode(?string $streetcode): self
    {
        $this->streetcode = $streetcode;

        return $this;
    }

    public function getStreetcode(): ?string
    {
        return $this->streetcode;
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
