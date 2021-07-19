<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTimeImmutable;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddressObject;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для сущности 'Классификатор адресообразующих элементов'.
 *
 * @internal
 */
class AddressObjectTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new AddressObject();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'aoid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'aoguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'parentguid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'previd' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'nextid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'code' => $this->createFakeData()->word(),
            'formalname' => $this->createFakeData()->word(),
            'offname' => $this->createFakeData()->word(),
            'shortname' => $this->createFakeData()->word(),
            'aolevel' => $this->createFakeData()->numberBetween(1, 1000000),
            'regioncode' => $this->createFakeData()->word(),
            'areacode' => $this->createFakeData()->word(),
            'autocode' => $this->createFakeData()->word(),
            'citycode' => $this->createFakeData()->word(),
            'ctarcode' => $this->createFakeData()->word(),
            'placecode' => $this->createFakeData()->word(),
            'plancode' => $this->createFakeData()->word(),
            'streetcode' => $this->createFakeData()->word(),
            'extrcode' => $this->createFakeData()->word(),
            'sextcode' => $this->createFakeData()->word(),
            'plaincode' => $this->createFakeData()->word(),
            'currstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'actstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'livestatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'centstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'operstatus' => $this->createFakeData()->numberBetween(1, 1000000),
            'ifnsfl' => $this->createFakeData()->word(),
            'ifnsul' => $this->createFakeData()->word(),
            'terrifnsfl' => $this->createFakeData()->word(),
            'terrifnsul' => $this->createFakeData()->word(),
            'okato' => $this->createFakeData()->word(),
            'oktmo' => $this->createFakeData()->word(),
            'postalcode' => $this->createFakeData()->word(),
            'startdate' => new DateTimeImmutable(),
            'enddate' => new DateTimeImmutable(),
            'updatedate' => new DateTimeImmutable(),
            'divtype' => $this->createFakeData()->numberBetween(1, 1000000),
            'normdoc' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
        ];
    }
}
