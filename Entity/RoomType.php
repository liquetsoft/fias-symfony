<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Перечень типов комнат.
 *
 * @ORM\MappedSuperclass
 */
class RoomType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $rmtypeid = 0;

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

    public function setRmtypeid(int $rmtypeid): self
    {
        $this->rmtypeid = $rmtypeid;

        return $this;
    }

    public function getRmtypeid(): int
    {
        return $this->rmtypeid;
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
