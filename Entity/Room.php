<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Комнаты.
 *
 * @ORM\MappedSuperclass
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    private $roomid;

    /**
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    private $roomguid;

    /**
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    private $houseguid;

    /**
     * @ORM\Column(type="string", length=2)
     *
     * @var string
     */
    private $regioncode = '';

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @var string
     */
    private $flatnumber = '';

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $flattype = 0;

    /**
     * @ORM\Column(type="string", length=6)
     *
     * @var string
     */
    private $postalcode = '';

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $startdate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $enddate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $updatedate;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $operstatus = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $livestatus = '';

    /**
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    private $normdoc;

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

    public function setPostalcode(string $postalcode): self
    {
        $this->postalcode = $postalcode;

        return $this;
    }

    public function getPostalcode(): string
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

    public function setOperstatus(string $operstatus): self
    {
        $this->operstatus = $operstatus;

        return $this;
    }

    public function getOperstatus(): string
    {
        return $this->operstatus;
    }

    public function setLivestatus(string $livestatus): self
    {
        $this->livestatus = $livestatus;

        return $this;
    }

    public function getLivestatus(): string
    {
        return $this->livestatus;
    }

    public function setNormdoc(UuidInterface $normdoc): self
    {
        $this->normdoc = $normdoc;

        return $this;
    }

    public function getNormdoc(): UuidInterface
    {
        return $this->normdoc;
    }
}
