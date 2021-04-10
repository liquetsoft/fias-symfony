<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\StructureStatus;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Признак строения'.
 *
 * @internal
 */
class StructureStatusTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new StructureStatus();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'strstatid' => $this->createFakeData()->numberBetween(1, 1000000),
            'name' => $this->createFakeData()->word,
            'shortname' => $this->createFakeData()->word,
        ];
    }
}
