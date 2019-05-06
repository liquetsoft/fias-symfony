<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статусы.
 *
 * @ORM\MappedSuperclass
 */
class OperationStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    protected $operstatid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name = '';

    public function setOperstatid(int $operstatid): self
    {
        $this->operstatid = $operstatid;

        return $this;
    }

    public function getOperstatid(): int
    {
        return $this->operstatid;
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
