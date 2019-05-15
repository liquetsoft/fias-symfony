<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use DateTimeInterface;

/**
 * Тест для сущности 'Текущаяверсия ФИАС'.
 */
class FiasVersionTest extends EntityCase
{
    /**
     * Пустой тест ради покрытия.
     */
    public function testGetId()
    {
        $entity = $this->createEntity();

        $this->assertNull($entity->getId());
    }

    /**
     * Проверяет, что в событии задается дата создания.
     */
    public function testOnPrePersist()
    {
        $entity = $this->createEntity();

        $this->assertNull($entity->getCreatedAt());
        $entity->onPrePersist();
        $this->assertInstanceOf(DateTimeInterface::class, $entity->getCreatedAt());
    }

    /**
     * @inheritdoc
     */
    protected function createEntity()
    {
        return new FiasVersion;
    }

    /**
     * @inheritdoc
     */
    protected function accessorsProvider(): array
    {
        return [
            'version' => $this->createFakeData()->numberBetween(1, 100000),
            'url' => $this->createFakeData()->url,
        ];
    }
}
