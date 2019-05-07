<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Типы нормативных документов.
 *
 * @ORM\MappedSuperclass
 */
class NormativeDocumentType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    protected $ndtypeid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name = '';

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
