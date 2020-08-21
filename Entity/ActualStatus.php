<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статус актуальности ФИАС.
 *
 * @ORM\MappedSuperclass
 */
class ActualStatus
{
    /**
     * Идентификатор статуса (ключ).
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $actstatid = 0;

    /**
     * Наименование
     * 0 – Не актуальный
     * 1 – Актуальный (последняя запись по адресному объекту).
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string
     */
    protected $name = '';

    public function setActstatid(int $actstatid): self
    {
        $this->actstatid = $actstatid;

        return $this;
    }

    public function getActstatid(): int
    {
        return $this->actstatid;
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
