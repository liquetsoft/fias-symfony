<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Типы квартир.
 *
 * @ORM\MappedSuperclass
 */
class FlatType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $fltypeid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $shortname = '';

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
