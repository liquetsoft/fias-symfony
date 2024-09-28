<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AdmHierarchy;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения по иерархии в административном делении'.
 *
 * @internal
 */
class AdmHierarchyTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new AdmHierarchy();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => $this->createFakeData()->numberBetween(1, 1000000),
            'objectid' => $this->createFakeData()->numberBetween(1, 1000000),
            'parentobjid' => $this->createFakeData()->numberBetween(1, 1000000),
            'changeid' => $this->createFakeData()->numberBetween(1, 1000000),
            'regioncode' => $this->createFakeData()->word(),
            'areacode' => $this->createFakeData()->word(),
            'citycode' => $this->createFakeData()->word(),
            'placecode' => $this->createFakeData()->word(),
            'plancode' => $this->createFakeData()->word(),
            'streetcode' => $this->createFakeData()->word(),
            'previd' => $this->createFakeData()->numberBetween(1, 1000000),
            'nextid' => $this->createFakeData()->numberBetween(1, 1000000),
            'updatedate' => new \DateTimeImmutable(),
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
            'isactive' => $this->createFakeData()->numberBetween(1, 1000000),
            'path' => $this->createFakeData()->word(),
        ];
    }
}
