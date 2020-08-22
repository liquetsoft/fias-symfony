<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTime;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\House;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для сущности 'Сведения по номерам домов улиц городов и населенных пунктов'.
 */
class HouseTest extends EntityCase
{
    /**
     * @inheritdoc
     */
    protected function createEntity()
    {
        return new House();
    }

    /**
     * @inheritdoc
     */
    protected function accessorsProvider(): array
    {
        return [
            'houseid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'houseguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'aoguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'housenum' => $this->createFakeData()->word,
            'strstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'eststatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'statstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'ifnsfl' => $this->createFakeData()->word,
            'ifnsul' => $this->createFakeData()->word,
            'okato' => $this->createFakeData()->word,
            'oktmo' => $this->createFakeData()->word,
            'postalcode' => $this->createFakeData()->word,
            'startdate' => new DateTime(),
            'enddate' => new DateTime(),
            'updatedate' => new DateTime(),
            'counter' => $this->createFakeData()->numberBetween(1, 1000000),
            'divtype' => $this->createFakeData()->numberBetween(1, 1000000),
            'regioncode' => $this->createFakeData()->word,
            'terrifnsfl' => $this->createFakeData()->word,
            'terrifnsul' => $this->createFakeData()->word,
            'buildnum' => $this->createFakeData()->word,
            'strucnum' => $this->createFakeData()->word,
            'normdoc' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'cadnum' => $this->createFakeData()->word,
        ];
    }
}
