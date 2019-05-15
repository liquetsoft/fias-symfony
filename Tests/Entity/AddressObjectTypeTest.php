<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddressObjectType;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Типы объектов в адресах'.
 */
class AddressObjectTypeTest extends EntityCase
{
    /**
     * @inheritdoc
     */
    protected function createEntity()
    {
        return new AddressObjectType;
    }

    /**
     * @inheritdoc
     */
    protected function accessorsProvider(): array
    {
        return [
            'kodtst' => $this->createFakeData()->numberBetween(1, 1000000),
            'level' => $this->createFakeData()->numberBetween(1, 1000000),
            'socrname' => $this->createFakeData()->word,
            'scname' => $this->createFakeData()->word,
        ];
    }
}
