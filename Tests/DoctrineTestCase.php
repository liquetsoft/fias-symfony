<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\ToolsException;

/**
 * Базовый класс для тестирования запросов с doctrine.
 */
abstract class DoctrineTestCase extends BaseCase
{
    /**
     * @var EntityManager|null
     */
    private $entityManager;

    /**
     * @var EntityManager|null
     */
    private $internalEntityManager;

    /**
     * Проверяет, что сущность существует в базе данных.
     *
     * @param object $entity
     * @param string $message
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws MappingException
     */
    public function assertDoctrineHasEntity(object $entity, $message = 'Failed asserting that entity can be found by Doctrine.'): void
    {
        $em = $this->getEntityManager();

        $className = get_class($entity);
        $meta = $em->getClassMetadata($className);
        $identifiers = $meta->getIdentifierValues($entity);
        $entityFromDoctrine = $em->find($className, $identifiers);

        $this->assertThat(
            $entityFromDoctrine,
            $this->logicalAnd(
                $this->logicalNot($this->isNull()),
                $this->isInstanceOf($className),
                $this->callback(function ($testedEntity) use ($meta, $entity) {
                    $isSame = true;

                    $fieldNames = $meta->getFieldNames();
                    foreach ($fieldNames as $fieldName) {
                        $valueBase = $meta->getFieldValue($entity, $fieldName);
                        $valueTest = $meta->getFieldValue($testedEntity, $fieldName);
                        if ($valueBase != $valueTest) {
                            $isSame = false;
                            break;
                        }
                    }

                    return $isSame;
                })
            ),
            $message
        );
    }

    /**
     * Проверяет, что сущность не существует в базе данных.
     *
     * @param object $entity
     * @param string $message
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws MappingException
     */
    public function assertDoctrineHasNotEntity(object $entity, $message = "Failed asserting that entity can't be found by Doctrine."): void
    {
        $em = $this->getEntityManager();

        $className = get_class($entity);
        $meta = $em->getClassMetadata($className);
        $identifiers = $meta->getIdentifierValues($entity);
        $entityFromDoctrine = $em->find($className, $identifiers);

        $this->assertThat(
            $entityFromDoctrine,
            $this->isNull(),
            $message
        );
    }

    /**
     * Записывает новую сузность в базу данных.
     *
     * @param object $entity
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws MappingException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistEntity(object $entity): void
    {
        $em = $this->getEntityManager();

        $em->persist($entity);
        $em->flush();
    }

    /**
     * Возвразает объект EntityManager для тестов.
     *
     * @return EntityManager
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws MappingException
     */
    protected function getEntityManager(): EntityManager
    {
        if ($this->entityManager === null) {
            $this->entityManager = $this->createEntityManager();
        }

        $this->entityManager->clear();

        return $this->entityManager;
    }

    /**
     * Создает EntityManager для тестов.
     *
     * @return EntityManager
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     */
    private function createEntityManager(): EntityManager
    {
        $paths = [
            __DIR__ . '/MockEntities',
        ];

        $cache = new ArrayCache();

        $driver = new AnnotationDriver(new AnnotationReader(), $paths);

        $config = Setup::createAnnotationMetadataConfiguration($paths, false);
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setMetadataDriverImpl($driver);

        $connection = [
            'driver' => getenv('DB_DRIVER'),
            'path' => getenv('DB_PATH'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
        ];

        $em = EntityManager::create($connection, $config);

        $schema = new SchemaTool($em);
        $schema->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $schema->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $em;
    }
}
