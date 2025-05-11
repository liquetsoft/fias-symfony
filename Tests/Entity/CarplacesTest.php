<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Carplaces;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для сущности 'Сведения по машино-местам'.
 *
 * @internal
 */
class CarplacesTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createEntity(): object
    {
        return new Carplaces();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'objectid' => 123321,
            'objectguid' => $this->mock(Uuid::class),
            'changeid' => 123321,
            'number' => 'test string',
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
