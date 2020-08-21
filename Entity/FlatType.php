<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Тип помещения.
 *
 * @ORM\MappedSuperclass
 */
class FlatType
{
    /**
     * Тип помещения.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $fltypeid = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     *
     * @var string
     */
    protected string $name = '';

    /**
     * Краткое наименование.
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     * @var string|null
     */
    protected ?string $shortname = null;

    public function setFltypeid(int $fltypeid): self
    {
        $this->fltypeid = $fltypeid;

        return $this;
    }

    public function getFltypeid(): int
    {
        return $this->fltypeid;
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

    public function setShortname(?string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }
}
