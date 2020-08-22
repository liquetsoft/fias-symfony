<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Serializer;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\BaseCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager\DoctrineVersionManager;
use RuntimeException;

/**
 * Тест для объекта, который обновляет и получает текущую версию.
 */
class DoctrineVersionManagerTest extends BaseCase
{
    /**
     * Проверяет, что объект правильно задает текущую версию.
     */
    public function testSetCurrentVersion()
    {
        $version = $this->createFakeData()->numberBetween(1, 1000);
        $url = $this->createFakeData()->url;

        $info = $this->getMockBuilder(InformerResponse::class)->getMock();
        $info->method('getVersion')->will($this->returnValue($version));
        $info->method('getUrl')->will($this->returnValue($url));

        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->expects($this->at(0))->method('persist')->with($this->callback(function ($entity) use ($version, $url) {
            return $entity->getVersion() === $version && $entity->getUrl() === $url;
        }));
        $em->expects($this->at(1))->method('flush');
        $em->expects($this->at(2))->method('clear');

        $versionManager = new DoctrineVersionManager($em, DoctrineVersionManagerMockObject::class);
        $versionManager->setCurrentVersion($info);
    }

    /**
     * Проверяет, что объект выбросит исключение, если задан неверный класс сущности.
     */
    public function testSetCurrentVersionWrongEntityException()
    {
        $info = $this->getMockBuilder(InformerResponse::class)->getMock();
        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $versionManager = new DoctrineVersionManager($em, 'test');

        $this->expectException(RuntimeException::class);
        $versionManager->setCurrentVersion($info);
    }

    /**
     * Проверяет, что объект правильно получает текущую версию.
     */
    public function testGetCurrentVersion()
    {
        $version = $this->createFakeData()->numberBetween(1, 1000);
        $url = $this->createFakeData()->url;

        $item = new DoctrineVersionManagerMockObject();
        $item->setVersion($version);
        $item->setUrl($url);

        $repo = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $repo->method('findOneBy')->will($this->returnValue($item));

        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->method('getRepository')->will($this->returnCallback(function ($class) use ($repo) {
            return $class === DoctrineVersionManagerMockObject::class ? $repo : null;
        }));

        $versionManager = new DoctrineVersionManager($em, DoctrineVersionManagerMockObject::class);
        $versionResponse = $versionManager->getCurrentVersion();

        $this->assertSame($version, $versionResponse->getVersion());
        $this->assertSame($url, $versionResponse->getUrl());
    }

    /**
     * Проверяет, что объект выбросит исключение, если задан неверный класс сущности.
     */
    public function testGetCurrentVersionWrongEntityException()
    {
        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();

        $versionManager = new DoctrineVersionManager($em, 'test');

        $this->expectException(RuntimeException::class);
        $versionResponse = $versionManager->getCurrentVersion();
    }
}

/**
 * Мок для проверки менеджера версий.
 */
class DoctrineVersionManagerMockObject extends FiasVersion
{
}
