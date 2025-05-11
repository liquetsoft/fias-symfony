<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\Param;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения о классификаторе параметров адресообразующих элементов и объектов недвижимости'.
 *
 * @internal
 */
class ParamTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createEntity(): object
    {
        return new Param();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'objectid' => 123321,
            'changeid' => 123321,
            'changeidend' => 123321,
            'typeid' => 123321,
            'value' => 'test string',
            'updatedate' => new \DateTimeImmutable(),
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
        ];
    }
}
