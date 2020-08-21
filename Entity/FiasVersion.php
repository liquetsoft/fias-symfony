<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Версия ФИАС.
 *
 * @ORM\MappedSuperclass
 */
class FiasVersion
{
    /**
     * Номер версии ФИАС.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $version = 0;

    /**
     * Ссылка для загрузки указанной версии ФИАС.
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected string $url = '';

    /**
     * Дата создания.
     *
     * @ORM\Column(type="datetime", nullable=false)
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $created = null;

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

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }
}
