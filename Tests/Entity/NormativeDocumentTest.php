<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use DateTime;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocument;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;
use Ramsey\Uuid\UuidInterface;

/**
 * Тест для сущности 'Сведения по нормативному документу, являющемуся основанием присвоения адресному элементу наименования'.
 */
class NormativeDocumentTest extends EntityCase
{
    /**
     * @inheritdoc
     */
    protected function createEntity()
    {
        return new NormativeDocument();
    }

    /**
     * @inheritdoc
     */
    protected function accessorsProvider(): array
    {
        return [
            'normdocid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
            'docname' => $this->createFakeData()->word,
            'docdate' => new DateTime(),
            'docnum' => $this->createFakeData()->word,
            'doctype' => $this->createFakeData()->numberBetween(1, 1000000),
            'docimgid' => $this->getMockBuilder(UuidInterface::class)->disableOriginalConstructor()->getMock(),
        ];
    }
}
