<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статус центра.
 *
 * @ORM\MappedSuperclass
 */
class CenterStatus
{
    /**
     * Идентификатор статуса.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $centerstid = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string
     */
    protected string $name = '';

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
