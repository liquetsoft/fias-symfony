<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ChangeHistory;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для сущности 'Сведения по истории изменений'.
 *
 * @internal
 */
class ChangeHistoryTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new ChangeHistory();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'changeid' => 123321,
            'objectid' => 123321,
            'adrobjectid' => $this->mock(Uuid::class),
            'opertypeid' => 123321,
            'ndocid' => 123321,
            'changedate' => new \DateTimeImmutable(),
        ];
    }
}
