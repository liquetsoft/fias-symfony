<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Перечень видов строений.
 *
 * @ORM\MappedSuperclass
 */
class StructureStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $strstatid = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $shortname = '';

    public function setStrstatid(int $strstatid): self
    {
        $this->strstatid = $strstatid;

        return $this;
    }

    public function getStrstatid(): int
    {
        return $this->strstatid;
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

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getShortname(): string
    {
        return $this->shortname;
    }
}
