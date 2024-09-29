<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Storage;

use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Storage\Storage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\StorageTestMockEntity;

/**
 * Тест для проверки записи данных в базу.
 */
abstract class AbstractDoctrineStorageCase extends DoctrineTestCase
{
    /**
     * Создает объект хранилища.
     */
    abstract protected function createStorage(?EntityManager $em = null, int $batch = 1): Storage;

    /**
     * Проверяет, что хранилище верно определяет сущности doctrine.
     *
     * @dataProvider provideSupports
     */
    public function testSupports(object $entity, bool $expected): void
    {
        $storage = $this->createStorage();
        $res = $storage->supports($entity);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'entity' => [
                new StorageTestMockEntity(),
                true,
            ],
            'random object' => [
                new \stdClass(),
                false,
            ],
        ];
    }

    /**
     * Проверяет, что хранилище верно определяет классы ущностей doctrine.
     *
     * @dataProvider provideSupportsClass
     */
    public function testSupportsClass(string $class, bool $expected): void
    {
        $storage = $this->createStorage();
        $res = $storage->supportsClass($class);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsClass(): array
    {
        return [
            'entity class' => [
                StorageTestMockEntity::class,
                true,
            ],
            'random class' => [
                \stdClass::class,
                false,
            ],
            'random string' => [
                'non_existed_123',
                false,
            ],
        ];
    }

    /**
     * Проверяет вставку записей в БД.
     */
    public function testInsert(): void
    {
        $zeroEntity = new StorageTestMockEntity();
        $zeroEntity->setTestId(0);
        $zeroEntity->setTestName('test_0');
        $zeroEntity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($zeroEntity);

        $entity = new StorageTestMockEntity();
        $entity->setTestId(1);
        $entity->setTestName('test_1');
        $entity->setStartdate(new \DateTime('2019-11-11 11:11:11'));

        $secondEntity = new StorageTestMockEntity();
        $secondEntity->setTestId(2);
        $secondEntity->setTestName('test_2');
        $secondEntity->setStartdate(new \DateTime('2019-11-11 11:11:11'));

        $thirdEntity = new StorageTestMockEntity();
        $thirdEntity->setTestId(3);
        $thirdEntity->setTestName('test_3');
        $thirdEntity->setStartdate(new \DateTime('2019-11-11 11:33:33'));

        $storage = $this->createStorage(null, 2);
        $storage->start();
        $storage->insert($entity);
        $storage->insert($secondEntity);
        $storage->insert($thirdEntity);
        $storage->stop();

        $this->assertDoctrineHasEntity($zeroEntity);
        $this->assertDoctrineHasEntity($entity);
        $this->assertDoctrineHasEntity($secondEntity);
        $this->assertDoctrineHasEntity($thirdEntity);
    }

    /**
     * Проверяет удаление записей из БД.
     */
    public function testDelete(): void
    {
        $entity = new StorageTestMockEntity();
        $entity->setTestId(4);
        $entity->setTestName('test_4');
        $entity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($entity);

        $secondEntity = new StorageTestMockEntity();
        $secondEntity->setTestId(5);
        $secondEntity->setTestName('test_5');
        $secondEntity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($secondEntity);

        $storage = $this->createStorage();
        $storage->start();
        $storage->delete($entity);
        $storage->stop();

        $this->assertDoctrineHasNotEntity($entity);
        $this->assertDoctrineHasEntity($secondEntity);
    }

    /**
     * Проверяет обновление записей из БД.
     */
    public function testUpsert(): void
    {
        $entity = new StorageTestMockEntity();
        $entity->setTestId(6);
        $entity->setTestName('test_6');
        $entity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($entity);
        $entity->setTestName('test_6_updated');

        $secondEntity = new StorageTestMockEntity();
        $secondEntity->setTestId(7);
        $secondEntity->setTestName('test_7');
        $secondEntity->setStartdate(new \DateTime('2019-11-11 11:11:11'));

        $thirdEntity = new StorageTestMockEntity();
        $thirdEntity->setTestId(8);
        $thirdEntity->setTestName('test_8');
        $thirdEntity->setStartdate(new \DateTime('2019-11-11 11:33:33'));

        $fourthEntity = new StorageTestMockEntity();
        $fourthEntity->setTestId(9);
        $fourthEntity->setTestName('test_9');
        $fourthEntity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($fourthEntity);
        $fourthEntity->setTestName('test_9_updated');

        $storage = $this->createStorage(null, 3);
        $storage->start();
        $storage->upsert($entity);
        $storage->upsert($secondEntity);
        $storage->upsert($thirdEntity);
        $storage->upsert($fourthEntity);
        $storage->stop();

        $this->assertDoctrineHasEntity($entity);
        $this->assertDoctrineHasEntity($secondEntity);
        $this->assertDoctrineHasEntity($thirdEntity);
        $this->assertDoctrineHasEntity($fourthEntity);
    }

    /**
     * Проверяет, что хранилище очищаеи базу данных.
     */
    public function testTruncate(): void
    {
        $entity = new StorageTestMockEntity();
        $entity->setTestId(10);
        $entity->setTestName('test_10');
        $entity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($entity);

        $secondEntity = new StorageTestMockEntity();
        $secondEntity->setTestId(11);
        $secondEntity->setTestName('test_11');
        $secondEntity->setStartdate(new \DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($secondEntity);

        $this->createStorage()->truncate(StorageTestMockEntity::class);

        $this->assertDoctrineHasNotEntity($entity);
        $this->assertDoctrineHasNotEntity($secondEntity);
    }
}
