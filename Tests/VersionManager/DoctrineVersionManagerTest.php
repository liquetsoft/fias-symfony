<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\FiasInformer\FiasInformerResponse;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\VersionManagerTestMockEntity;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager\DoctrineVersionManager;

/**
 * Тест для объекта, который обновляет и получает текущую версию.
 *
 * @internal
 */
final class DoctrineVersionManagerTest extends DoctrineTestCase
{
    /**
     * Проверяет, что объект правильно задает текущую версию.
     */
    public function testSetCurrentVersion(): void
    {
        $version = 123;
        $fullUrl = 'https://test.ru/full';
        $deltaUrl = 'https://test.ru/delta';

        $info = $this->mock(FiasInformerResponse::class);
        $info->method('getVersion')->willReturn($version);
        $info->method('getFullUrl')->willReturn($fullUrl);
        $info->method('getDeltaUrl')->willReturn($deltaUrl);

        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            VersionManagerTestMockEntity::class
        );
        $versionManager->setCurrentVersion($info);

        $versionEntity = new VersionManagerTestMockEntity();
        $versionEntity->setVersion($version);
        $versionEntity->setFullurl($fullUrl);
        $versionEntity->setDeltaurl($deltaUrl);

        $this->assertDoctrineHasEntity($versionEntity);
    }

    /**
     * Проверяет, что объект выбросит исключение, если задан неверный класс сущности.
     */
    public function testSetCurrentVersionWrongEntityException(): void
    {
        $info = $this->mock(FiasInformerResponse::class);

        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            'test'
        );

        $this->expectException(\RuntimeException::class);
        $versionManager->setCurrentVersion($info);
    }

    /**
     * Проверяет, что объект правильно получает текущую версию.
     */
    public function testGetCurrentVersion(): void
    {
        $version = 123;
        $fullUrl = 'https://test.ru/full';
        $deltaUrl = 'https://test.ru/delta';

        $info = $this->mock(FiasInformerResponse::class);
        $info->method('getVersion')->willReturn($version);
        $info->method('getFullUrl')->willReturn($fullUrl);
        $info->method('getDeltaUrl')->willReturn($deltaUrl);

        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            VersionManagerTestMockEntity::class
        );
        $versionManager->setCurrentVersion($info);
        $versionResponse = $versionManager->getCurrentVersion();

        $this->assertNotNull($versionResponse);
        $this->assertSame($version, $versionResponse->getVersion());
        $this->assertSame($fullUrl, $versionResponse->getFullUrl());
        $this->assertSame($deltaUrl, $versionResponse->getDeltaUrl());
    }

    /**
     * Проверяет, что объект выбросит исключение, если задан неверный класс сущности.
     */
    public function testGetCurrentVersionWrongEntityException(): void
    {
        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            'test'
        );

        $this->expectException(\RuntimeException::class);
        $versionManager->getCurrentVersion();
    }
}
