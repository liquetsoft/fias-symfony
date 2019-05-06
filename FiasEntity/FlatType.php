<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\FiasEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Типы квартир.
 *
 * @ORM\Table(name="liquetsoft_fias_flattype")
 * @ORM\Entity
 */
class FlatType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    private $fltypeid = 0;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $shortname = '';

    public function setFltypeid(int $fltypeid): self
    {
        $this->fltypeid = $fltypeid;

        return $this;
    }

    public function getFltypeid(): int
    {
        return $this->fltypeid;
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

    public function setShortname(string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getShortname(): string
    {
        return $this->shortname;
    }
}
