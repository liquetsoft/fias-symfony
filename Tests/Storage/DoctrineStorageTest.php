<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Storage;

use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\DoctrineStorage;
use RuntimeException;
use stdClass;

/**
 * Тест для проверки записи данных в базу.
 */
class DoctrineStorageTest extends AbstractDoctrineStorageTest
{
    /**
     * Проверяет перехват исключения при завершении записи.
     */
    public function testStopException()
    {
        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->method('flush')->will($this->throwException(new RuntimeException()));

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->stop();
    }

    /**
     * Проверяет перехват исключения при вставке записей в БД.
     */
    public function testInsertException()
    {
        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->method('persist')->will($this->throwException(new RuntimeException()));

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->insert(new stdClass());
    }

    /**
     * Проверяет перехват исключения при удалении записей в БД.
     */
    public function testDeleteException()
    {
        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->method('getClassMetadata')->will($this->throwException(new RuntimeException()));

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->delete(new stdClass());
    }

    /**
     * Проверяет перехват исключения при обновлении записей в БД.
     */
    public function testUpsertException()
    {
        $em = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $em->method('persist')->will($this->throwException(new RuntimeException()));
        $em->method('getClassMetadata')->will($this->throwException(new RuntimeException()));

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->upsert(new stdClass());
    }

    /**
     * {@inheritDoc}
     */
    protected function createStorage(?EntityManager $em = null, int $batch = 1): Storage
    {
        $em = $em ?: $this->getEntityManager();

        return new DoctrineStorage($em, $batch);
    }
}
