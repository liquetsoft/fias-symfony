<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTimeImmutable;
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
    protected function createEntity()
    {
        return new NormativeDocs();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => $this->createFakeData()->numberBetween(1, 1000000),
            'name' => $this->createFakeData()->word(),
            'date' => new DateTimeImmutable(),
            'number' => $this->createFakeData()->word(),
            'type' => $this->createFakeData()->numberBetween(1, 1000000),
            'kind' => $this->createFakeData()->numberBetween(1, 1000000),
            'updatedate' => new DateTimeImmutable(),
            'orgname' => $this->createFakeData()->word(),
            'regnum' => $this->createFakeData()->word(),
            'regdate' => new DateTimeImmutable(),
            'accdate' => new DateTimeImmutable(),
            'comment' => $this->createFakeData()->word(),
        ];
    }
}
