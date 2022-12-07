<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Apartments;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для сущности 'Сведения по помещениям'.
 *
 * @internal
 */
class ApartmentsTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new Apartments();
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
            'number' => $this->createFakeData()->word(),
            'aparttype' => $this->createFakeData()->numberBetween(1, 1000000),
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
