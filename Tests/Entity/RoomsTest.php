<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Rooms;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для сущности 'Сведения по комнатам'.
 *
 * @internal
 */
class RoomsTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new Rooms();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'objectid' => 123321,
            'objectguid' => $this->mock(Uuid::class),
            'changeid' => 123321,
            'number' => 'test string',
            'roomtype' => 123321,
            'opertypeid' => 123321,
            'previd' => 123321,
            'nextid' => 123321,
            'updatedate' => new \DateTimeImmutable(),
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
            'isactual' => 123321,
            'isactive' => 123321,
        ];
    }
}
