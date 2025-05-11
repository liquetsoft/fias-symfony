<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Storage;

use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\DoctrineStorage;

/**
 * Тест для проверки записи данных в базу.
 *
 * @internal
 */
final class DoctrineStorageTest extends AbstractDoctrineStorageCase
{
    /**
     * Проверяет перехват исключения при вставке записей в БД.
     */
    public function testInsertException(): void
    {
        $em = $this->mock(EntityManager::class);
        $em->expects($this->once())
            ->method('persist')
            ->willThrowException(new \RuntimeException());

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->insert(new \stdClass());
    }

    /**
     * Проверяет перехват исключения при удалении записей в БД.
     */
    public function testDeleteException(): void
    {
        $em = $this->mock(EntityManager::class);
        $em->expects($this->once())
            ->method('getClassMetadata')
            ->willThrowException(new \RuntimeException());

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->delete(new \stdClass());
    }

    /**
     * Проверяет перехват исключения при обновлении записей в БД.
     */
    public function testUpsertException(): void
    {
        $em = $this->mock(EntityManager::class);
        $em->expects($this->any())
            ->method('persist')
            ->willThrowException(new \RuntimeException());
        $em->expects($this->any())
            ->method('getClassMetadata')
            ->willThrowException(new \RuntimeException());

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->upsert(new \stdClass());
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createStorage(?EntityManager $em = null, int $batch = 1): Storage
    {
        $em = $em ?: $this->getEntityManager();

        return new DoctrineStorage($em, $batch);
    }
}
