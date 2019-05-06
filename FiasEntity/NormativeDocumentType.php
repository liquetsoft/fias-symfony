<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\FiasEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Типы нормативных документов.
 *
 * @ORM\Table(name="liquetsoft_fias_normativedocumenttype")
 * @ORM\Entity
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
    private $ndtypeid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name = '';

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
