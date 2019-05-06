<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\FiasEntity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Нормативные документы.
 *
 * @ORM\Table(name="liquetsoft_fias_normativedocument")
 * @ORM\Entity
 */
class NormativeDocument
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    private $normdocid;

    /**
     * @ORM\Column(type="string", length=500)
     *
     * @var string
     */
    private $docname = '';

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $docdate;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $docnum = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $doctype = '';

    public function setNormdocid(UuidInterface $normdocid): self
    {
        $this->normdocid = $normdocid;

        return $this;
    }

    public function getNormdocid(): UuidInterface
    {
        return $this->normdocid;
    }

    public function setDocname(string $docname): self
    {
        $this->docname = $docname;

        return $this;
    }

    public function getDocname(): string
    {
        return $this->docname;
    }

    public function setDocdate(DateTimeInterface $docdate): self
    {
        $this->docdate = $docdate;

        return $this;
    }

    public function getDocdate(): DateTimeInterface
    {
        return $this->docdate;
    }

    public function setDocnum(string $docnum): self
    {
        $this->docnum = $docnum;

        return $this;
    }

    public function getDocnum(): string
    {
        return $this->docnum;
    }

    public function setDoctype(string $doctype): self
    {
        $this->doctype = $doctype;

        return $this;
    }

    public function getDoctype(): string
    {
        return $this->doctype;
    }
}
