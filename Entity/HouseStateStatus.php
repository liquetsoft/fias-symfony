<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Перечень возможных состояний объектов недвижимости.
 *
 * @ORM\MappedSuperclass
 */
class HouseStateStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $housestid = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $name = '';

    public function setHousestid(int $housestid): self
    {
        $this->housestid = $housestid;

        return $this;
    }

    public function getHousestid(): int
    {
        return $this->housestid;
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
