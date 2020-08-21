<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Классификатор помещениях.
 *
 * @ORM\MappedSuperclass
 */
class Room
{
    /**
     * Уникальный идентификатор записи. Ключевое поле.
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $roomid;

    /**
     * Глобальный уникальный идентификатор адресного объекта (помещения).
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $roomguid;

    /**
     * Идентификатор родительского объекта (дома).
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $houseguid;

    /**
     * Код региона.
     *
     * @ORM\Column(type="string", length=2, nullable=false)
     *
     * @var string
     */
    protected $regioncode = '';

    /**
     * Номер помещения или офиса.
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @var string
     */
    protected $flatnumber = '';

    /**
     * Тип помещения.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $flattype = 0;

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
     * Дата  внесения записи.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $updatedate;

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
     * Признак действующего адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $livestatus = 0;

    /**
     * Внешний ключ на нормативный документ.
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $normdoc;

    /**
     * Номер комнаты.
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string|null
     */
    protected $roomnumber;

    /**
     * Тип комнаты.
     *
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    protected $roomtype;

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
     * Кадастровый номер помещения.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string|null
     */
    protected $cadnum;

    /**
     * Кадастровый номер комнаты в помещении.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string|null
     */
    protected $roomcadnum;

    public function setRoomid(UuidInterface $roomid): self
    {
        $this->roomid = $roomid;

        return $this;
    }

    public function getRoomid(): UuidInterface
    {
        return $this->roomid;
    }

    public function setRoomguid(UuidInterface $roomguid): self
    {
        $this->roomguid = $roomguid;

        return $this;
    }

    public function getRoomguid(): UuidInterface
    {
        return $this->roomguid;
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

    public function setRegioncode(string $regioncode): self
    {
        $this->regioncode = $regioncode;

        return $this;
    }

    public function getRegioncode(): string
    {
        return $this->regioncode;
    }

    public function setFlatnumber(string $flatnumber): self
    {
        $this->flatnumber = $flatnumber;

        return $this;
    }

    public function getFlatnumber(): string
    {
        return $this->flatnumber;
    }

    public function setFlattype(int $flattype): self
    {
        $this->flattype = $flattype;

        return $this;
    }

    public function getFlattype(): int
    {
        return $this->flattype;
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

    public function setOperstatus(int $operstatus): self
    {
        $this->operstatus = $operstatus;

        return $this;
    }

    public function getOperstatus(): int
    {
        return $this->operstatus;
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

    public function setNormdoc(?UuidInterface $normdoc): self
    {
        $this->normdoc = $normdoc;

        return $this;
    }

    public function getNormdoc(): ?UuidInterface
    {
        return $this->normdoc;
    }

    public function setRoomnumber(?string $roomnumber): self
    {
        $this->roomnumber = $roomnumber;

        return $this;
    }

    public function getRoomnumber(): ?string
    {
        return $this->roomnumber;
    }

    public function setRoomtype(?int $roomtype): self
    {
        $this->roomtype = $roomtype;

        return $this;
    }

    public function getRoomtype(): ?int
    {
        return $this->roomtype;
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

    public function setRoomcadnum(?string $roomcadnum): self
    {
        $this->roomcadnum = $roomcadnum;

        return $this;
    }

    public function getRoomcadnum(): ?string
    {
        return $this->roomcadnum;
    }
}
