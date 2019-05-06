<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\FiasEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Типы объектов в адресах.
 *
 * @ORM\Table(name="liquetsoft_fias_addressobjecttype")
 * @ORM\Entity
 */
class AddressObjectType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    private $kod_t_st = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $level = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $socrname = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $scname = '';

    public function setKod_t_st(int $kod_t_st): self
    {
        $this->kod_t_st = $kod_t_st;

        return $this;
    }

    public function getKod_t_st(): int
    {
        return $this->kod_t_st;
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

    public function setScname(string $scname): self
    {
        $this->scname = $scname;

        return $this;
    }

    public function getScname(): string
    {
        return $this->scname;
    }
}
