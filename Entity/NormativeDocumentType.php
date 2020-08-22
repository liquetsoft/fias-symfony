<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Тип нормативного документа.
 *
 * @ORM\MappedSuperclass
 */
class NormativeDocumentType
{
    /**
     * Идентификатор записи (ключ).
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $ndtypeid = 0;

    /**
     * Наименование типа нормативного документа.
     *
     * @ORM\Column(type="string", length=250, nullable=false)
     *
     * @var string
     */
    protected string $name = '';

    public function setNdtypeid(int $ndtypeid): self
    {
        $this->ndtypeid = $ndtypeid;

        return $this;
    }

    public function getNdtypeid(): int
    {
        return $this->ndtypeid;
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
}
