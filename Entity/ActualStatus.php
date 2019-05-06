<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статусы.
 *
 * @ORM\MappedSuperclass
 */
class ActualStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    protected $actstatid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name = '';

    public function setActstatid(int $actstatid): self
    {
        $this->actstatid = $actstatid;

        return $this;
    }

    public function getActstatid(): int
    {
        return $this->actstatid;
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
