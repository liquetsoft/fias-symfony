<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocs;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения о нормативном документе, являющемся основанием присвоения адресному элементу наименования'.
 *
 * @internal
 */
class NormativeDocsTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new NormativeDocs();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'name' => 'test string',
            'date' => new \DateTimeImmutable(),
            'number' => 'test string',
            'type' => 123321,
            'kind' => 123321,
            'updatedate' => new \DateTimeImmutable(),
            'orgname' => 'test string',
            'regnum' => 'test string',
            'regdate' => new \DateTimeImmutable(),
            'accdate' => new \DateTimeImmutable(),
            'comment' => 'test string',
        ];
    }
}
