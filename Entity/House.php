<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Сведения по номерам домов улиц городов и населенных пунктов.
 *
 * @ORM\MappedSuperclass
 */
class House
{
    /**
     * Уникальный идентификатор записи дома.
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $houseid;

    /**
     * Глобальный уникальный идентификатор дома.
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $houseguid;

    /**
     * Guid записи родительского объекта (улицы, города, населенного пункта и т.п.).
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $aoguid;

    /**
     * Номер дома.
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     * @var string|null
     */
    protected $housenum;

    /**
     * Признак строения.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected $strstatus;

    /**
     * Признак владения.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $eststatus = 0;

    /**
     * Состояние дома.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $statstatus = 0;

    /**
     * Код ИФНС ФЛ.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected $ifnsfl;

    /**
     * Код ИФНС ЮЛ.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected $ifnsul;

    /**
     * OKATO.
     *
     * @ORM\Column(type="string", length=11, nullable=true)
     *
     * @var string|null
     */
    protected $okato;

    /**
     * OKTMO.
     *
     * @ORM\Column(type="string", length=11, nullable=true)
     *
     * @var string|null
     */
    protected $oktmo;

    /**
     * Почтовый индекс.
     *
     * @ORM\Column(type="string", length=6, nullable=true)
     *
     * @var string|null
     */
    protected $postalcode;

    /**
     * Начало действия записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $startdate;

    /**
     * Окончание действия записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $enddate;

    /**
     * Дата время внесения записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $updatedate;

    /**
     * Счетчик записей домов для КЛАДР 4.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $counter = 0;

    /**
     * Тип адресации:
     * 0 - не определено
     * 1 - муниципальный;
     * 2 - административно-территориальный.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $divtype = 0;

    /**
     * Код региона.
     *
     * @ORM\Column(type="string", length=2, nullable=true)
     *
     * @var string|null
     */
    protected $regioncode;

    /**
     * Код территориального участка ИФНС ФЛ.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected $terrifnsfl;

    /**
     * Код территориального участка ИФНС ЮЛ.
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @var string|null
     */
    protected $terrifnsul;

    /**
     * Номер корпуса.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string|null
     */
    protected $buildnum;

    /**
     * Номер строения.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string|null
     */
    protected $strucnum;

    /**
     * Внешний ключ на нормативный документ.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $normdoc;

    /**
     * Кадастровый номер.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string|null
     */
    protected $cadnum;

    public function setHouseid(UuidInterface $houseid): self
    {
        $this->houseid = $houseid;

        return $this;
    }

    public function getHouseid(): UuidInterface
    {
        return $this->houseid;
    }

    public function setHouseguid(UuidInterface $houseguid): self
    {
        $this->houseguid = $houseguid;

        return $this;
    }

    public function getHouseguid(): UuidInterface
    {
        return $this->houseguid;
    }

    public function setAoguid(UuidInterface $aoguid): self
    {
        $this->aoguid = $aoguid;

        return $this;
    }

    public function getAoguid(): UuidInterface
    {
        return $this->aoguid;
    }

    public function setHousenum(?string $housenum): self
    {
        $this->housenum = $housenum;

        return $this;
    }

    public function getHousenum(): ?string
    {
        return $this->housenum;
    }

    public function setStrstatus(?int $strstatus): self
    {
        $this->strstatus = $strstatus;

        return $this;
    }

    public function getStrstatus(): ?int
    {
        return $this->strstatus;
    }

    public function setEststatus(int $eststatus): self
    {
        $this->eststatus = $eststatus;

        return $this;
    }

    public function getEststatus(): int
    {
        return $this->eststatus;
    }

    public function setStatstatus(int $statstatus): self
    {
        $this->statstatus = $statstatus;

        return $this;
    }

    public function getStatstatus(): int
    {
        return $this->statstatus;
    }

    public function setIfnsfl(?string $ifnsfl): self
    {
        $this->ifnsfl = $ifnsfl;

        return $this;
    }

    public function getIfnsfl(): ?string
    {
        return $this->ifnsfl;
    }

    public function setIfnsul(?string $ifnsul): self
    {
        $this->ifnsul = $ifnsul;

        return $this;
    }

    public function getIfnsul(): ?string
    {
        return $this->ifnsul;
    }

    public function setOkato(?string $okato): self
    {
        $this->okato = $okato;

        return $this;
    }

    public function getOkato(): ?string
    {
        return $this->okato;
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

    public function setPostalcode(?string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getPostalcode(): ?string
    {
        return $this->postalcode;
    }

    public function setStartdate(DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getStartdate(): DateTimeInterface
    {
        return $this->startdate;
    }

    public function setEnddate(DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getEnddate(): DateTimeInterface
    {
        return $this->enddate;
    }

    public function setUpdatedate(DateTimeInterface $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getUpdatedate(): DateTimeInterface
    {
        return $this->updatedate;
    }

    public function setCounter(int $counter): self
    {
        $this->counter = $counter;

        return $this;
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function setDivtype(int $divtype): self
    {
        $this->divtype = $divtype;

        return $this;
    }

    public function getDivtype(): int
    {
        return $this->divtype;
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

    public function setTerrifnsfl(?string $terrifnsfl): self
    {
        $this->terrifnsfl = $terrifnsfl;

        return $this;
    }

    public function getTerrifnsfl(): ?string
    {
        return $this->terrifnsfl;
    }

    public function setTerrifnsul(?string $terrifnsul): self
    {
        $this->terrifnsul = $terrifnsul;

        return $this;
    }

    public function getTerrifnsul(): ?string
    {
        return $this->terrifnsul;
    }

    public function setBuildnum(?string $buildnum): self
    {
        $this->buildnum = $buildnum;

        return $this;
    }

    public function getBuildnum(): ?string
    {
        return $this->buildnum;
    }

    public function setStrucnum(?string $strucnum): self
    {
        $this->strucnum = $strucnum;

        return $this;
    }

    public function getStrucnum(): ?string
    {
        return $this->strucnum;
    }

    public function setNormdoc(?UuidInterface $normdoc): self
    {
        $this->normdoc = $normdoc;

        return $this;
    }

    public function getNormdoc(): ?UuidInterface
    {
        return $this->normdoc;
    }

    public function setCadnum(?string $cadnum): self
    {
        $this->cadnum = $cadnum;

        return $this;
    }

    public function getCadnum(): ?string
    {
        return $this->cadnum;
    }
}
