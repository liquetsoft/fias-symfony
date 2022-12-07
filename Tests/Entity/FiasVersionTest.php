<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Версия ФИАС'.
 *
 * @internal
 */
class FiasVersionTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new FiasVersion();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'version' => $this->createFakeData()->numberBetween(1, 1000000),
            'url' => $this->createFakeData()->word(),
            'created' => new \DateTimeImmutable(),
        ];
    }
}
