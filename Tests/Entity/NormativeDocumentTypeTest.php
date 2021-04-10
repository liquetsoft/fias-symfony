<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocumentType;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Тип нормативного документа'.
 *
 * @internal
 */
class NormativeDocumentTypeTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new NormativeDocumentType();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'ndtypeid' => $this->createFakeData()->numberBetween(1, 1000000),
            'name' => $this->createFakeData()->word,
        ];
    }
}
