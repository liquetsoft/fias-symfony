<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Адреса.
 *
 * @ORM\MappedSuperclass
 */
class AddressObject
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    protected $aoid;

    /**
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    protected $aoguid;

    /**
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    protected $parentguid;

    /**
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    protected $nextid;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $formalname = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $offname = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $shortname = '';

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $aolevel = 0;

    /**
     * @ORM\Column(type="string", length=2)
     *
     * @var string
     */
    protected $regioncode = '';

    /**
     * @ORM\Column(type="string", length=3)
     *
     * @var string
     */
    protected $areacode = '';

    /**
     * @ORM\Column(type="string", length=1)
     *
     * @var string
     */
    protected $autocode = '';

    /**
     * @ORM\Column(type="string", length=3)
     *
     * @var string
     */
    protected $citycode = '';

    /**
     * @ORM\Column(type="string", length=3)
     *
     * @var string
     */
    protected $ctarcode = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $placecode = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $plancode = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $streetcode = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $extrcode = '';

    /**
     * @ORM\Column(type="string", length=3)
     *
     * @var string
     */
    protected $sextcode = '';

    /**
     * @ORM\Column(type="string", length=15)
     *
     * @var string
     */
    protected $plaincode = '';

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $currstatus = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $actstatus = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $livestatus = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $centstatus = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $operstatus = 0;

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $ifnsfl = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $ifnsul = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $terrifnsfl = '';

    /**
     * @ORM\Column(type="string", length=4)
     *
     * @var string
     */
    protected $terrifnsul = '';

    /**
     * @ORM\Column(type="string", length=11)
     *
     * @var string
     */
    protected $okato = '';

    /**
     * @ORM\Column(type="string", length=11)
     *
     * @var string
     */
    protected $oktmo = '';

    /**
     * @ORM\Column(type="string", length=6)
     *
     * @var string
     */
    protected $postalcode = '';

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    protected $startdate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    protected $enddate;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    protected $updatedate;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $divtype = 0;

    public function setAoid(UuidInterface $aoid): self
    {
        $this->aoid = $aoid;

        return $this;
    }

    public function getAoid(): UuidInterface
    {
        return $this->aoid;
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

    public function setParentguid(UuidInterface $parentguid): self
    {
        $this->parentguid = $parentguid;

        return $this;
    }

    public function getParentguid(): UuidInterface
    {
        return $this->parentguid;
    }

    public function setNextid(UuidInterface $nextid): self
    {
        $this->nextid = $nextid;

        return $this;
    }

    public function getNextid(): UuidInterface
    {
        return $this->nextid;
    }

    public function setFormalname(string $formalname): self
    {
        $this->formalname = $formalname;

        return $this;
    }

    public function getFormalname(): string
    {
        return $this->formalname;
    }

    public function setOffname(string $offname): self
    {
        $this->offname = $offname;

        return $this;
    }

    public function getOffname(): string
    {
        return $this->offname;
    }

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getShortname(): string
    {
        return $this->shortname;
    }

    public function setAolevel(int $aolevel): self
    {
        $this->aolevel = $aolevel;

        return $this;
    }

    public function getAolevel(): int
    {
        return $this->aolevel;
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

    public function setAreacode(string $areacode): self
    {
        $this->areacode = $areacode;

        return $this;
    }

    public function getAreacode(): string
    {
        return $this->areacode;
    }

    public function setAutocode(string $autocode): self
    {
        $this->autocode = $autocode;

        return $this;
    }

    public function getAutocode(): string
    {
        return $this->autocode;
    }

    public function setCitycode(string $citycode): self
    {
        $this->citycode = $citycode;

        return $this;
    }

    public function getCitycode(): string
    {
        return $this->citycode;
    }

    public function setCtarcode(string $ctarcode): self
    {
        $this->ctarcode = $ctarcode;

        return $this;
    }

    public function getCtarcode(): string
    {
        return $this->ctarcode;
    }

    public function setPlacecode(string $placecode): self
    {
        $this->placecode = $placecode;

        return $this;
    }

    public function getPlacecode(): string
    {
        return $this->placecode;
    }

    public function setPlancode(string $plancode): self
    {
        $this->plancode = $plancode;

        return $this;
    }

    public function getPlancode(): string
    {
        return $this->plancode;
    }

    public function setStreetcode(string $streetcode): self
    {
        $this->streetcode = $streetcode;

        return $this;
    }

    public function getStreetcode(): string
    {
        return $this->streetcode;
    }

    public function setExtrcode(string $extrcode): self
    {
        $this->extrcode = $extrcode;

        return $this;
    }

    public function getExtrcode(): string
    {
        return $this->extrcode;
    }

    public function setSextcode(string $sextcode): self
    {
        $this->sextcode = $sextcode;

        return $this;
    }

    public function getSextcode(): string
    {
        return $this->sextcode;
    }

    public function setPlaincode(string $plaincode): self
    {
        $this->plaincode = $plaincode;

        return $this;
    }

    public function getPlaincode(): string
    {
        return $this->plaincode;
    }

    public function setCurrstatus(int $currstatus): self
    {
        $this->currstatus = $currstatus;

        return $this;
    }

    public function getCurrstatus(): int
    {
        return $this->currstatus;
    }

    public function setActstatus(int $actstatus): self
    {
        $this->actstatus = $actstatus;

        return $this;
    }

    public function getActstatus(): int
    {
        return $this->actstatus;
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

    public function setCentstatus(int $centstatus): self
    {
        $this->centstatus = $centstatus;

        return $this;
    }

    public function getCentstatus(): int
    {
        return $this->centstatus;
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

    public function setTerrifnsfl(string $terrifnsfl): self
    {
        $this->terrifnsfl = $terrifnsfl;

        return $this;
    }

    public function getTerrifnsfl(): string
    {
        return $this->terrifnsfl;
    }

    public function setTerrifnsul(string $terrifnsul): self
    {
        $this->terrifnsul = $terrifnsul;

        return $this;
    }

    public function getTerrifnsul(): string
    {
        return $this->terrifnsul;
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

    public function setDivtype(int $divtype): self
    {
        $this->divtype = $divtype;

        return $this;
    }

    public function getDivtype(): int
    {
        return $this->divtype;
    }
}
