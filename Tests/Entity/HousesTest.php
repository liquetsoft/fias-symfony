<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Houses;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для сущности 'Сведения по номерам домов улиц городов и населенных пунктов'.
 *
 * @internal
 */
class HousesTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new Houses();
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
            'housenum' => 'test string',
            'addnum1' => 'test string',
            'addnum2' => 'test string',
            'housetype' => 123321,
            'addtype1' => 123321,
            'addtype2' => 123321,
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
