<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность, которая хранит текущую версию ФИАС.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class FiasVersion
{
    /**
     * Уникальный идентификатор записи.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    protected $id;

    /**
     * Дата создания.
     *
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * Версия ФИАС.
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $version;

    /**
     * Ссылка на архив с обновлениями для данной версии.
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $url;

    public function __construct()
    {
        $this->version = 0;
        $this->url = '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Событие, которое срабатывает при добавлении записи в бд.
     *
     * Задает дату и время создания.
     *
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime;
    }
}
