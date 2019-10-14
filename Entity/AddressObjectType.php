<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Перечень полных, сокращённых наименований типов адресных элементов и уровней их классификации.
 *
 * @ORM\MappedSuperclass
 */
class AddressObjectType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $kodtst = 0;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $level = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $socrname = '';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    protected $scname;

    public function setKodtst(int $kodtst): self
    {
        $this->kodtst = $kodtst;

        return $this;
    }

    public function getKodtst(): int
    {
        return $this->kodtst;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setSocrname(string $socrname): self
    {
        $this->socrname = $socrname;

        return $this;
    }

    public function getSocrname(): string
    {
        return $this->socrname;
    }

    public function setScname(?string $scname): self
    {
        $this->scname = $scname;

        return $this;
    }

    public function getScname(): ?string
    {
        return $this->scname;
    }
}
