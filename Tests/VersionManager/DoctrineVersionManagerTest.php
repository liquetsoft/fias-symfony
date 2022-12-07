<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\VersionManagerTestMockEntity;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager\DoctrineVersionManager;

/**
 * Тест для объекта, который обновляет и получает текущую версию.
 *
 * @internal
 */
class DoctrineVersionManagerTest extends DoctrineTestCase
{
    /**
     * Проверяет, что объект правильно задает текущую версию.
     */
    public function testSetCurrentVersion(): void
    {
        $version = $this->createFakeData()->numberBetween(1, 1000);
        $url = $this->createFakeData()->url();

        $info = $this->getMockBuilder(InformerResponse::class)->getMock();
        $info->method('getVersion')->willReturn($version);
        $info->method('getUrl')->willReturn($url);

        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            VersionManagerTestMockEntity::class
        );
        $versionManager->setCurrentVersion($info);

        $versionEntity = new VersionManagerTestMockEntity();
        $versionEntity->setVersion($version);
        $versionEntity->setUrl($url);

        $this->assertDoctrineHasEntity($versionEntity);
    }

    /**
     * Проверяет, что объект выбросит исключение, если задан неверный класс сущности.
     */
    public function testSetCurrentVersionWrongEntityException(): void
    {
        $info = $this->getMockBuilder(InformerResponse::class)->getMock();

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
        $version = $this->createFakeData()->numberBetween(1, 1000);
        $url = $this->createFakeData()->url();

        $info = $this->getMockBuilder(InformerResponse::class)->getMock();
        $info->method('getVersion')->willReturn($version);
        $info->method('getUrl')->willReturn($url);

        $versionManager = new DoctrineVersionManager(
            $this->getEntityManager(),
            VersionManagerTestMockEntity::class
        );
        $versionManager->setCurrentVersion($info);
        $versionResponse = $versionManager->getCurrentVersion();

        $this->assertSame($version, $versionResponse->getVersion());
        $this->assertSame($url, $versionResponse->getUrl());
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
        $versionResponse = $versionManager->getCurrentVersion();
    }
}
