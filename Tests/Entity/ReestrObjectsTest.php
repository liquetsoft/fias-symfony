<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ReestrObjects;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Symfony\Component\Uid\Uuid;

/**
 * Тест для сущности 'Сведения об адресном элементе в части его идентификаторов'.
 *
 * @internal
 */
class ReestrObjectsTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new ReestrObjects();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'objectid' => $this->createFakeData()->numberBetween(1, 1000000),
            'createdate' => new \DateTimeImmutable(),
            'changeid' => $this->createFakeData()->numberBetween(1, 1000000),
            'levelid' => $this->createFakeData()->numberBetween(1, 1000000),
            'updatedate' => new \DateTimeImmutable(),
            'objectguid' => $this->getMockBuilder(Uuid::class)->disableOriginalConstructor()->getMock(),
            'isactive' => $this->createFakeData()->numberBetween(1, 1000000),
        ];
    }
}
