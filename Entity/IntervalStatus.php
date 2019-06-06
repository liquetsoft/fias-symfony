<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Перечень возможных значений интервалов домов (обычный, четный, нечетный).
 *
 * @ORM\MappedSuperclass
 */
class IntervalStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $intvstatid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name = '';

    public function setIntvstatid(int $intvstatid): self
    {
        $this->intvstatid = $intvstatid;

        return $this;
    }

    public function getIntvstatid(): int
    {
        return $this->intvstatid;
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
