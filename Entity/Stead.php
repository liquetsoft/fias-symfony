<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Сведения о земельных участках.
 *
 * @ORM\MappedSuperclass
 */
class Stead
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $steadguid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    protected $number;

    /**
     * @ORM\Column(type="string", length=2, nullable=false)
     *
     * @var string
     */
    protected $regioncode = '';

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     *
     * @var string|null
     */
    protected $postalcode;

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     *
     * @var string
     */
    protected $ifnsfl = '';

    /**
     * @ORM\Column(type="string", length=4, nullable=false)
     *
     * @var string
     */
    protected $ifnsul = '';

    /**
     * @ORM\Column(type="string", length=11, nullable=false)
     *
     * @var string
     */
    protected $okato = '';

    /**
     * @ORM\Column(type="string", length=11, nullable=false)
     *
     * @var string
     */
    protected $oktmo = '';

    /**
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $parentguid;

    /**
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $steadid;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $operstatus = '';

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $startdate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $enddate;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface
     */
    protected $updatedate;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $livestatus = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $divtype = '';

    /**
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected $normdoc;

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

    public function setIfnsfl(string $ifnsfl): self
    {
        $this->ifnsfl = $ifnsfl;

        return $this;
    }

    public function getIfnsfl(): string
    {
        return $this->ifnsfl;
    }

    public function setIfnsul(string $ifnsul): self
    {
        $this->ifnsul = $ifnsul;

        return $this;
    }

    public function getIfnsul(): string
    {
        return $this->ifnsul;
    }

    public function setOkato(string $okato): self
    {
        $this->okato = $okato;

        return $this;
    }

    public function getOkato(): string
    {
        return $this->okato;
    }

    public function setOktmo(string $oktmo): self
    {
        $this->oktmo = $oktmo;

        return $this;
    }

    public function getOktmo(): string
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

    public function setSteadid(?UuidInterface $steadid): self
    {
        $this->steadid = $steadid;

        return $this;
    }

    public function getSteadid(): ?UuidInterface
    {
        return $this->steadid;
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

    public function setLivestatus(string $livestatus): self
    {
        $this->livestatus = $livestatus;

        return $this;
    }

    public function getLivestatus(): string
    {
        return $this->livestatus;
    }

    public function setDivtype(string $divtype): self
    {
        $this->divtype = $divtype;

        return $this;
    }

    public function getDivtype(): string
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
}
