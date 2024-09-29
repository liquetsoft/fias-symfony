<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\MunHierarchy;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения по иерархии в муниципальном делении'.
 *
 * @internal
 */
class MunHierarchyTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new MunHierarchy();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'objectid' => 123321,
            'parentobjid' => 123321,
            'changeid' => 123321,
            'oktmo' => 'test string',
            'previd' => 123321,
            'nextid' => 123321,
            'updatedate' => new \DateTimeImmutable(),
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
            'isactive' => 123321,
            'path' => 'test string',
        ];
    }
}
