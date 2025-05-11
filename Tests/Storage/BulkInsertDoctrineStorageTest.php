<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Storage;

use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage\BulkInsertDoctrineStorage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\StorageTestMockEntity;

/**
 * Тест для проверки записи данных в базу с помощью bulk insert.
 *
 * @internal
 */
final class BulkInsertDoctrineStorageTest extends AbstractDoctrineStorageCase
{
    /**
     * Проверяет, что хранилище попробует отправить записи по одной, если произошла ошибка в бандле.
     */
    public function testBulkSafelyInsert(): void
    {
        $entity = new StorageTestMockEntity();
        $entity->setTestId(100);
        $entity->setTestName('test_1_100');
        $entity->setStartdate(new \DateTimeImmutable('2019-11-11 11:11:11'));

        $secondEntity = new StorageTestMockEntity();
        $secondEntity->setTestId(100);
        $secondEntity->setTestName('test_2_100');
        $secondEntity->setStartdate(new \DateTimeImmutable('2019-11-11 11:11:11'));

        $storage = $this->createStorage(null, 2);
        $storage->start();
        $storage->insert($entity);
        $storage->insert($secondEntity);
        $storage->stop();

        $this->assertDoctrineHasEntity($entity);
    }

    /**
     * Проверяет перехват исключения при вставке записей в БД.
     */
    public function testInsertException(): void
    {
        $em = $this->mock(EntityManager::class);
        $em->expects($this->once())
            ->method('getClassMetadata')
            ->willThrowException(new \RuntimeException());

        $storage = $this->createStorage($em);

        $this->expectException(StorageException::class);
        $storage->insert(new \stdClass());
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    protected function createStorage(?EntityManager $em = null, int $batch = 1): Storage
    {
        $em = $em ?: $this->getEntityManager();

        return new BulkInsertDoctrineStorage($em, $batch);
    }
}
