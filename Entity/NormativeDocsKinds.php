<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сведения по видам нормативных документов.
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class NormativeDocsKinds
{
    /**
     * Идентификатор записи.
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $id = 0;

    /**
     * Наименование.
     *
     * @ORM\Column(type="string", length=500, nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false, length: 500)]
    protected string $name = '';

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
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
