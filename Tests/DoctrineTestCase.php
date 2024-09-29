<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Базовый класс для тестирования запросов с doctrine.
 *
 * @internal
 */
abstract class DoctrineTestCase extends BaseCase
{
    private ?EntityManager $entityManager = null;

    /**
     * Проверяет, что сущность существует в базе данных.
     */
    public function assertDoctrineHasEntity(object $entity, string $message = 'Failed asserting that entity can be found by Doctrine'): void
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
     */
    public function assertDoctrineHasNotEntity(object $entity, string $message = "Failed asserting that entity can't be found by Doctrine"): void
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
     */
    public function persistEntity(object $entity): void
    {
        $em = $this->getEntityManager();

        $em->persist($entity);
        $em->flush();
    }

    /**
     * Возвразает объект EntityManager для тестов.
     */
    public function getEntityManager(): EntityManager
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
    private function unifyValueForCompare(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        } elseif ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d\TH:i:s.Z');
        } elseif (\is_object($value) && method_exists($value, '__toString')) {
            return (string) $value->__toString();
        }

        return (string) $value;
    }

    /**
     * Создает EntityManager для тестов.
     *
     * @psalm-suppress InvalidArgument
     */
    private function createEntityManager(): EntityManager
    {
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', UuidType::class);
        }

        $config = ORMSetup::createAttributeMetadataConfiguration(
            [
                __DIR__ . '/MockEntities',
            ],
            true,
            null
        );

        $connection = DriverManager::getConnection(
            [
                'driver' => getenv('DB_DRIVER'),
                'path' => getenv('DB_PATH'),
                'user' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'dbname' => getenv('DB_NAME'),
            ],
            $config
        );

        $em = new EntityManager($connection, $config);

        $schema = new SchemaTool($em);
        $schema->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $schema->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $em;
    }
}
