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
final class AdmHierarchyTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createEntity(): object
    {
        return new AdmHierarchy();
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
            'parentobjid' => 123321,
            'changeid' => 123321,
            'regioncode' => 'test string',
            'areacode' => 'test string',
            'citycode' => 'test string',
            'placecode' => 'test string',
            'plancode' => 'test string',
            'streetcode' => 'test string',
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
