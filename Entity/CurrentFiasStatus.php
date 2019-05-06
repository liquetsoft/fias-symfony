<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use DateTime;

/**
 * Статусы обновления ФИАС в системе.
 *
 * Сущность, которая фиксирует текущую версию фиас, а также все
 * попытки установки и обновления ФИАС в системе.
 *
 * @ORM\Table(name="liquetsoft_current_fias_status")
 * @ORM\Entity(repositoryClass="Liquetsoft\Fias\Symfony\FiasBundle\Repository\CurrentFiasStatusRepository")
 */
class CurrentFiasStatus
{
    /**
     * Уникальный идентификатор обновления.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * Дата обновления или установки.
     *
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * Версия ФИАС после данного обновления или установки.
     *
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $version;

    /**
     * Ссылка, по которой был скачан файл для обновления или установки.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $url;

    /**
     * Флаг, который будет выставлен в правду, если обновление или установка
     * прошла успешно.
     *
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $isCompleted;

    public function __construct()
    {
        $this->version = 0;
        $this->url = '';
        $this->createdAt = new DateTime;
        $this->isCompleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    public function getIsCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }
}
