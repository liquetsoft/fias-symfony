<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Статус действия.
 *
 * @ORM\MappedSuperclass
 */
class OperationStatus
{
    /**
     * Идентификатор статуса (ключ).
     *
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var int
     */
    protected $operstatid = 0;

    /**
     * Наименование
     * 01 – Инициация;
     * 10 – Добавление;
     * 20 – Изменение;
     * 21 – Групповое изменение;
     * 30 – Удаление;
     * 31 - Удаление вследствие удаления вышестоящего объекта;
     * 40 – Присоединение адресного объекта (слияние);
     * 41 – Переподчинение вследствие слияния вышестоящего объекта;
     * 42 - Прекращение существования вследствие присоединения к другому адресному объекту;
     * 43 - Создание нового адресного объекта в результате слияния адресных объектов;
     * 50 – Переподчинение;
     * 51 – Переподчинение вследствие переподчинения вышестоящего объекта;
     * 60 – Прекращение существования вследствие дробления;
     * 61 – Создание нового адресного объекта в результате дробления;
     * 70 – Восстановление объекта прекратившего существование.
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     *
     * @var string
     */
    protected $name = '';

    public function setOperstatid(int $operstatid): self
    {
        $this->operstatid = $operstatid;

        return $this;
    }

    public function getOperstatid(): int
    {
        return $this->operstatid;
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
