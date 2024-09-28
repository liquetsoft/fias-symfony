<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Базовый класс для тестирования запросов с doctrine.
 */
abstract class DoctrineTestCase extends BaseCase
{
    private ?EntityManager $entityManager = null;

    /**
     * Проверяет, что сущность существует в базе данных.
     *
     * @param string $message
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function assertDoctrineHasEntity(object $entity, $message = 'Failed asserting that entity can be found by Doctrine.'): void
    {
        $em = $this->getEntityManager();

        $className = \get_class($entity);
        $meta = $em->getClassMetadata($className);
        $identifiers = $meta->getIdentifierValues($entity);
        $entityFromDoctrine = $em->find($className, $identifiers);

        $this->assertThat(
            $entityFromDoctrine,
            $this->logicalAnd(
                $this->logicalNot($this->isNull()),
                $this->isInstanceOf($className),
                $this->callback(function (object $testedEntity) use ($meta, $entity) {
                    $isSame = true;

                    $fieldNames = $meta->getFieldNames();
                    foreach ($fieldNames as $fieldName) {
                        $valueBase = $this->unifyValueForCompare($meta->getFieldValue($entity, $fieldName));
                        $valueTest = $this->unifyValueForCompare($meta->getFieldValue($testedEntity, $fieldName));
                        if ($valueBase !== null && $valueBase !== $valueTest) {
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
     * @param string $message
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function assertDoctrineHasNotEntity(object $entity, $message = "Failed asserting that entity can't be found by Doctrine."): void
    {
        $em = $this->getEntityManager();

        $className = \get_class($entity);
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
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     * @throws OptimisticLockException
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
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
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
     * Приводит значения к общему типу для сравнения.
     */
    private function unifyValueForCompare($value)
    {
        $unified = $value;
        if ($value instanceof \DateTimeInterface) {
            $unified = $value->format('Y-m-d\TH:i:s.Z');
        } elseif (\is_object($value) && method_exists($value, '__toString')) {
            $unified = $value->__toString();
        }

        return $unified;
    }

    /**
     * Создает EntityManager для тестов.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws ToolsException
     *
     * @psalm-suppress ArgumentTypeCoercion
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

        if (!Type::hasType('uuid')) {
            Type::addType('uuid', UuidType::class);
        }

        $paths = [
            __DIR__ . '/MockEntities',
        ];
        $isDevMode = true;
        $proxyDir = null;
        $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir);

        $em = EntityManager::create($connection, $config);

        $schema = new SchemaTool($em);
        $schema->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $schema->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $em;
    }
}
