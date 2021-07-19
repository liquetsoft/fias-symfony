<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTimeImmutable;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocument;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для сущности 'Сведения по нормативному документу, являющемуся основанием присвоения адресному элементу наименования'.
 *
 * @internal
 */
class NormativeDocumentTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity()
    {
        return new NormativeDocument();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'normdocid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'docname' => $this->createFakeData()->word(),
            'docdate' => new DateTimeImmutable(),
            'docnum' => $this->createFakeData()->word(),
            'doctype' => $this->createFakeData()->numberBetween(1, 1000000),
            'docimgid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
        ];
    }
}
