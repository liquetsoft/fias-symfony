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
    protected function createEntity(): object
    {
        return new ReestrObjects();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'objectid' => 123321,
            'createdate' => new \DateTimeImmutable(),
            'changeid' => 123321,
            'levelid' => 123321,
            'updatedate' => new \DateTimeImmutable(),
            'objectguid' => $this->mock(Uuid::class),
            'isactive' => 123321,
        ];
    }
}
