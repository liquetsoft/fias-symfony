<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Классификатор земельных участков.
 *
 * @ORM\MappedSuperclass
 */
class Stead
{
    /**
     * Глобальный уникальный идентификатор адресного объекта (земельного участка).
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $steadguid;

    /**
     * Номер земельного участка.
     *
     * @ORM\Column(type="string", length=120, nullable=true)
     *
     * @var string|null
     */
    protected $number;

    /**
     * Код региона.
     *
     * @ORM\Column(type="string", length=2, nullable=false)
     *
     * @var string
     */
    protected $regioncode = '';

    /**
     * Почтовый индекс.
     *
     * @ORM\Column(type="string", length=6, nullable=true)
     *
     * @var string|null
     */
    protected $postalcode;

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
     * Идентификатор объекта родительского объекта.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $parentguid;

    /**
     * Уникальный идентификатор записи. Ключевое поле.
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $steadid;

    /**
     * Статус действия над записью – причина появления записи (см. описание таблицы OperationStatus):
     * 01 – Инициация;
     * 10 – Добавление;
     * 20 – Изменение;
     * 21 – Групповое изменение;
     * 30 – Удаление;
     * 31 - Удаление вследствие удаления вышестоящего объекта;
     * 40 – Присоединение адресного объекта (слияние);
     * 41 – Переподчинение вследствие слияния вышестоящего объекта;
     * 42 - Прекращение существования вследствие присоединения к другому адресному объекту;
     * 43 - Создание нового адресного объекта в результате слияния адресных объектов;
     * 50 – Переподчинение;
     * 51 – Переподчинение вследствие переподчинения вышестоящего объекта;
     * 60 – Прекращение существования вследствие дробления;
     * 61 – Создание нового адресного объекта в результате дробления.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $operstatus = 0;

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
     * Дата  внесения записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $updatedate;

    /**
     * Признак действующего адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $livestatus = 0;

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
     * Внешний ключ на нормативный документ.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $normdoc;

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
     * Идентификатор записи связывания с предыдушей исторической записью.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $previd;

    /**
     * Идентификатор записи  связывания с последующей исторической записью.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $nextid;

    /**
     * Кадастровый номер.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string|null
     */
    protected $cadnum;

    public function setSteadguid(UuidInterface $steadguid): self
    {
        $this->steadguid = $steadguid;

        return $this;
    }

    public function getSteadguid(): UuidInterface
    {
        return $this->steadguid;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setRegioncode(string $regioncode): self
    {
        $this->regioncode = $regioncode;

        return $this;
    }

    public function getRegioncode(): string
    {
        return $this->regioncode;
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

    public function setParentguid(?UuidInterface $parentguid): self
    {
        $this->parentguid = $parentguid;

        return $this;
    }

    public function getParentguid(): ?UuidInterface
    {
        return $this->parentguid;
    }

    public function setSteadid(UuidInterface $steadid): self
    {
        $this->steadid = $steadid;

        return $this;
    }

    public function getSteadid(): UuidInterface
    {
        return $this->steadid;
    }

    public function setOperstatus(int $operstatus): self
    {
        $this->operstatus = $operstatus;

        return $this;
    }

    public function getOperstatus(): int
    {
        return $this->operstatus;
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

    public function setLivestatus(int $livestatus): self
    {
        $this->livestatus = $livestatus;

        return $this;
    }

    public function getLivestatus(): int
    {
        return $this->livestatus;
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

    public function setNormdoc(?UuidInterface $normdoc): self
    {
        $this->normdoc = $normdoc;

        return $this;
    }

    public function getNormdoc(): ?UuidInterface
    {
        return $this->normdoc;
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

    public function setPrevid(?UuidInterface $previd): self
    {
        $this->previd = $previd;

        return $this;
    }

    public function getPrevid(): ?UuidInterface
    {
        return $this->previd;
    }

    public function setNextid(?UuidInterface $nextid): self
    {
        $this->nextid = $nextid;

        return $this;
    }

    public function getNextid(): ?UuidInterface
    {
        return $this->nextid;
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
