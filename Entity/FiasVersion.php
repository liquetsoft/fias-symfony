<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Версия ФИАС.
 *
 * @psalm-consistent-constructor
 *
 * @ORM\MappedSuperclass
 */
#[ORM\MappedSuperclass]
class FiasVersion
{
    /**
     * Номер версии ФИАС.
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @ORM\Id
     */
    #[ORM\Column(type: 'integer', nullable: false)]
    #[ORM\Id]
    protected int $version = 0;

    /**
     * Ссылка для загрузки указанной версии ФИАС.
     *
     * @ORM\Column(type="string", nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false, length: 255)]
    protected string $fullurl = '';

    /**
     * Ссылка для загрузки изменений для указанной версии ФИАС.
     *
     * @ORM\Column(type="string", nullable=false)
     */
    #[ORM\Column(type: 'string', nullable: false, length: 255)]
    protected string $deltaurl = '';

    /**
     * Дата создания.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?\DateTimeImmutable $created = null;

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setFullurl(string $fullurl): self
    {
        $this->fullurl = $fullurl;

        return $this;
    }

    public function getFullurl(): string
    {
        return $this->fullurl;
    }

    public function setDeltaurl(string $deltaurl): self
    {
        $this->deltaurl = $deltaurl;

        return $this;
    }

    public function getDeltaurl(): string
    {
        return $this->deltaurl;
    }

    public function setCreated(\DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): \DateTimeImmutable
    {
        if ($this->created === null) {
            throw new \InvalidArgumentException("Parameter 'created' isn't set.");
        }

        return $this->created;
    }
}
