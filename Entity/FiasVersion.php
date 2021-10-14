<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Версия ФИАС.
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
    protected string $url = '';

    /**
     * Дата создания.
     *
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    protected ?DateTimeImmutable $created = null;

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setCreated(DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): DateTimeImmutable
    {
        if ($this->created === null) {
            throw new InvalidArgumentException("Parameter 'created' isn't set.");
        }

        return $this->created;
    }
}
