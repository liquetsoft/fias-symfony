<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения о нормативном документе, являющемся основанием присвоения адресному элементу наименования.
 *
 * @ORM\MappedSuperclass
 */
class NormativeDocs
{
    /**
     * Уникальный идентификатор документа.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * Наименование документа.
     *
     * @ORM\Column(type="string", length=8000, nullable=false)
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Дата документа.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $date = null;

    /**
     * Номер документа.
     *
     * @ORM\Column(type="string", length=150, nullable=false)
     *
     * @var string
     */
    protected string $number = '';

    /**
     * Тип документа.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $type = 0;

    /**
     * Вид документа.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $kind = 0;

    /**
     * Дата обновления.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $updatedate = null;

    /**
     * Наименование органа создвшего нормативный документ.
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    protected ?string $orgname = null;

    /**
     * Номер государственной регистрации.
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string|null
     */
    protected ?string $regnum = null;

    /**
     * Дата государственной регистрации.
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $regdate = null;

    /**
     * Дата вступления в силу нормативного документа.
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     *
     * @var DateTimeImmutable|null
     */
    protected ?DateTimeImmutable $accdate = null;

    /**
     * Комментарий.
     *
     * @ORM\Column(type="string", length=8000, nullable=true)
     *
     * @var string|null
     */
    protected ?string $comment = null;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDate(DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setKind(int $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getKind(): int
    {
        return $this->kind;
    }

    public function setUpdatedate(DateTimeImmutable $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getUpdatedate(): ?DateTimeImmutable
    {
        return $this->updatedate;
    }

    public function setOrgname(?string $orgname): self
    {
        $this->orgname = $orgname;

        return $this;
    }

    public function getOrgname(): ?string
    {
        return $this->orgname;
    }

    public function setRegnum(?string $regnum): self
    {
        $this->regnum = $regnum;

        return $this;
    }

    public function getRegnum(): ?string
    {
        return $this->regnum;
    }

    public function setRegdate(?DateTimeImmutable $regdate): self
    {
        $this->regdate = $regdate;

        return $this;
    }

    public function getRegdate(): ?DateTimeImmutable
    {
        return $this->regdate;
    }

    public function setAccdate(?DateTimeImmutable $accdate): self
    {
        $this->accdate = $accdate;

        return $this;
    }

    public function getAccdate(): ?DateTimeImmutable
    {
        return $this->accdate;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
