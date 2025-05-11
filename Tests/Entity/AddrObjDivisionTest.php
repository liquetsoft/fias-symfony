<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObjDivision;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения по операциям переподчинения'.
 *
 * @internal
 */
class AddrObjDivisionTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createEntity(): object
    {
        return new AddrObjDivision();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'parentid' => 123321,
            'childid' => 123321,
            'changeid' => 123321,
        ];
    }
}
