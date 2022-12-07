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
    protected function createEntity()
    {
        return new Houses();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => $this->createFakeData()->numberBetween(1, 1000000),
            'objectid' => $this->createFakeData()->numberBetween(1, 1000000),
            'objectguid' => $this->getMockBuilder(Uuid::class)->disableOriginalConstructor()->getMock(),
            'changeid' => $this->createFakeData()->numberBetween(1, 1000000),
            'housenum' => $this->createFakeData()->word(),
            'addnum1' => $this->createFakeData()->word(),
            'addnum2' => $this->createFakeData()->word(),
            'housetype' => $this->createFakeData()->numberBetween(1, 1000000),
            'addtype1' => $this->createFakeData()->numberBetween(1, 1000000),
            'addtype2' => $this->createFakeData()->numberBetween(1, 1000000),
            'opertypeid' => $this->createFakeData()->numberBetween(1, 1000000),
            'previd' => $this->createFakeData()->numberBetween(1, 1000000),
            'nextid' => $this->createFakeData()->numberBetween(1, 1000000),
            'updatedate' => new \DateTimeImmutable(),
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
            'isactual' => $this->createFakeData()->numberBetween(1, 1000000),
            'isactive' => $this->createFakeData()->numberBetween(1, 1000000),
        ];
    }
}
