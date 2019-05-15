<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTime;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Room;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для сущности 'Комнаты'.
 */
class RoomTest extends EntityCase
{
    /**
     * @inheritdoc
     */
    protected function createEntity()
    {
        return new Room;
    }

    /**
     * @inheritdoc
     */
    protected function accessorsProvider(): array
    {
        return [
            'roomid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'roomguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'houseguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'regioncode' => $this->createFakeData()->word,
            'flatnumber' => $this->createFakeData()->word,
            'flattype' => $this->createFakeData()->numberBetween(1, 1000000),
            'postalcode' => $this->createFakeData()->word,
            'startdate' => new DateTime,
            'enddate' => new DateTime,
            'updatedate' => new DateTime,
            'operstatus' => $this->createFakeData()->word,
            'livestatus' => $this->createFakeData()->word,
            'normdoc' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
        ];
    }
}
