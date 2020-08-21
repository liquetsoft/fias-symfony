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
     * Идентификатор нормативного документа.
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @var UuidInterface|null
     */
    protected ?UuidInterface $normdocid = null;

    /**
     * Наименование документа.
     *
     * @ORM\Column(type="string", length=1000, nullable=true)
     *
     * @var string|null
     */
    protected ?string $docname = null;

    /**
     * Дата документа.
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $docdate = null;

    /**
     * Номер документа.
     *
     * @ORM\Column(type="string", length=250, nullable=true)
     *
     * @var string|null
     */
    protected ?string $docnum = null;

    /**
     * Тип документа.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $doctype = 0;

    /**
     * Идентификатор образа (внешний ключ).
     *
     * @ORM\Column(type="uuid", nullable=true)
     *
     * @var UuidInterface|null
     */
    protected ?UuidInterface $docimgid = null;

    public function setNormdocid(UuidInterface $normdocid): self
    {
        $this->normdocid = $normdocid;

        return $this;
    }

    public function getNormdocid(): ?UuidInterface
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

    public function setDoctype(int $doctype): self
    {
        $this->doctype = $doctype;

        return $this;
    }

    public function getDoctype(): int
    {
        return $this->doctype;
    }

    public function setDocimgid(?UuidInterface $docimgid): self
    {
        $this->docimgid = $docimgid;

        return $this;
    }

    public function getDocimgid(): ?UuidInterface
    {
        return $this->docimgid;
    }
}
