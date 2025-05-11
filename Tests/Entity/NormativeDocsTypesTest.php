<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocsTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения по типам нормативных документов'.
 *
 * @internal
 */
class NormativeDocsTypesTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createEntity(): object
    {
        return new NormativeDocsTypes();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'name' => 'test string',
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
        ];
    }
}
