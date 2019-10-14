<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * Сведения по нормативному документу, являющемуся основанием присвоения адресному элементу наименования.
 *
 * @ORM\MappedSuperclass
 */
class NormativeDocument
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface
     */
    protected $normdocid;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     *
     * @var string|null
     */
    protected $docname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTimeInterface|null
     */
    protected $docdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    protected $docnum;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $doctype = '';

    public function setNormdocid(UuidInterface $normdocid): self
    {
        $this->normdocid = $normdocid;

        return $this;
    }

    public function getNormdocid(): UuidInterface
    {
        return $this->normdocid;
    }

    public function setDocname(?string $docname): self
    {
        $this->docname = $docname;

        return $this;
    }

    public function getDocname(): ?string
    {
        return $this->docname;
    }

    public function setDocdate(?DateTimeInterface $docdate): self
    {
        $this->docdate = $docdate;

        return $this;
    }

    public function getDocdate(): ?DateTimeInterface
    {
        return $this->docdate;
    }

    public function setDocnum(?string $docnum): self
    {
        $this->docnum = $docnum;

        return $this;
    }

    public function getDocnum(): ?string
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
