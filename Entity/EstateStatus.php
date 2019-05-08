<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статусы.
 *
 * @ORM\MappedSuperclass
 */
class EstateStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $eststatid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name = '';

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
}
