<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Признак владения.
 *
 * @ORM\MappedSuperclass
 */
class EstateStatus
{
    /**
     * Признак владения.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $eststatid = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     *
     * @var string
     */
    protected $name = '';

    /**
     * Краткое наименование.
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     *
     * @var string|null
     */
    protected $shortname;

    public function setEststatid(int $eststatid): self
    {
        $this->eststatid = $eststatid;

        return $this;
    }

    public function getEststatid(): int
    {
        return $this->eststatid;
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
