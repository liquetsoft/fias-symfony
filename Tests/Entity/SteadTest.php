<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTimeImmutable;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Stead;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для сущности 'Классификатор земельных участков'.
 *
 * @internal
 */
class SteadTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new Stead();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'steadguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'number' => $this->createFakeData()->word,
            'regioncode' => $this->createFakeData()->word,
            'postalcode' => $this->createFakeData()->word,
            'ifnsfl' => $this->createFakeData()->word,
            'ifnsul' => $this->createFakeData()->word,
            'okato' => $this->createFakeData()->word,
            'oktmo' => $this->createFakeData()->word,
            'parentguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'steadid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'operstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'startdate' => new DateTimeImmutable(),
            'enddate' => new DateTimeImmutable(),
            'updatedate' => new DateTimeImmutable(),
            'livestatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'divtype' => $this->createFakeData()->numberBetween(1, 1000000),
            'normdoc' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'terrifnsfl' => $this->createFakeData()->word,
            'terrifnsul' => $this->createFakeData()->word,
            'previd' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'nextid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'cadnum' => $this->createFakeData()->word,
        ];
    }
}
