<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Tools\ToolsException;

/**
 * Базовый класс для тестирования запросов с doctrine.
 */
abstract class DoctrineTestCase extends BaseCase
{
    private ?EntityManager $entityManager = null;

    /**
     * Проверяет, что сущность существует в базе данных.
     *
     * @param object $entity
     * @param string $message
     *
     * @throws AnnotationException
     * @throws MappingException
     * @throws ORMException
     * @throws ToolsException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
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
     * @throws MappingException
     * @throws ORMException
     * @throws ToolsException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
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
     * @throws MappingException
     * @throws ORMException
     * @throws ToolsException
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
        $connection = [
            'driver' => getenv('DB_DRIVER'),
            'path' => getenv('DB_PATH'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'dbname' => getenv('DB_NAME'),
        ];

        $paths = [
            __DIR__ . '/MockEntities',
        ];
        $isDevMode = true;
        $proxyDir = null;
        $cache = new ArrayCache();
        $useSimpleAnnotationReader = false;
        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

        $em = EntityManager::create($connection, $config);

        $schema = new SchemaTool($em);
        $schema->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $schema->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $em;
    }
}
