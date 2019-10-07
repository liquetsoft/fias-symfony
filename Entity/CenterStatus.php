<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Перечень возможных статусов (центров) адресных объектов административных единиц.
 *
 * @ORM\MappedSuperclass
 */
class CenterStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $centerstid = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $name = '';

    public function setCenterstid(int $centerstid): self
    {
        $this->centerstid = $centerstid;

        return $this;
    }

    public function getCenterstid(): int
    {
        return $this->centerstid;
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
