<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Тип адресного объекта.
 *
 * @ORM\MappedSuperclass
 */
class AddressObjectType
{
    /**
     * Ключевое поле.
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=4, nullable=false)
     *
     * @var string
     */
    protected string $kodtst = '';

    /**
     * Уровень адресного объекта.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $level = 0;

    /**
     * Полное наименование типа объекта.
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     *
     * @var string
     */
    protected string $socrname = '';

    /**
     * Краткое наименование типа объекта.
     *
     * @ORM\Column(type="string", length=10, nullable=true)
     *
     * @var string|null
     */
    protected ?string $scname = null;

    public function setKodtst(string $kodtst): self
    {
        $this->kodtst = $kodtst;

        return $this;
    }

    public function getKodtst(): string
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
