<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статус актуальности КЛАДР 4.0.
 *
 * @ORM\MappedSuperclass
 */
class CurrentStatus
{
    /**
     * Идентификатор статуса (ключ).
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected int $curentstid = 0;

    /**
     * Наименование (0 - актуальный, 1-50, 2-98 – исторический (кроме 51), 51 - переподчиненный, 99 - несуществующий).
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string
     */
    protected string $name = '';

    public function setCurentstid(int $curentstid): self
    {
        $this->curentstid = $curentstid;

        return $this;
    }

    public function getCurentstid(): int
    {
        return $this->curentstid;
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
