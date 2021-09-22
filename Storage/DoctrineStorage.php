<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use Throwable;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 */
class DoctrineStorage implements Storage
{
    protected EntityManager $em;

    protected int $batchCount;

    /**
     * @var array<string, bool>
     */
    private array $supportedClasses = [];

    /**
     * @var array<string, array<int, object>>
     */
    private array $upsertData = [];

    public function __construct(EntityManager $em, int $batchCount = 1000)
    {
        $this->em = $em;
        $this->batchCount = $batchCount;
    }

    /**
     * {@inheritDoc}
     */
    public function start(): void
    {
    }

    /**
     * {@inheritDoc}
     */
    public function stop(): void
    {
        $this->checkAndFlushUpsert(true);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(object $entity): bool
    {
        return $this->supportsClass(
            $this->getEntityName($entity)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass(string $class): bool
    {
        if (!isset($this->supportedClasses[$class])) {
            try {
                $this->getEntityMeta($class);
                $this->supportedClasses[$class] = true;
            } catch (Throwable $e) {
                $this->supportedClasses[$class] = false;
            }
        }

        return $this->supportedClasses[$class];
    }

    /**
     * {@inheritDoc}
     */
    public function insert(object $entity): void
    {
        try {
            $this->em->persist($entity);
            $this->em->flush();
            $this->em->detach($entity);
        } catch (Throwable $e) {
            throw $this->convertToStorageException($e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(object $entity): void
    {
        try {
            $entityFromDoctrine = $this->em->find(
                $this->getEntityName($entity),
                $this->getIdentifiersFromEntity($entity)
            );
            if ($entityFromDoctrine) {
                $this->em->remove($entityFromDoctrine);
                $this->em->flush();
                $this->em->detach($entityFromDoctrine);
            }
        } catch (Throwable $e) {
            throw $this->convertToStorageException($e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function upsert(object $entity): void
    {
        $meta = $this->getEntityMeta($entity);
        $id = $meta->getFieldValue(
            $entity,
            $meta->getSingleIdentifierFieldName()
        );

        $this->upsertData[$this->getEntityName($entity)][$id] = $entity;

        $this->checkAndFlushUpsert();
    }

    /**
     * {@inheritDoc}
     */
    public function truncate(string $entityClassName): void
    {
        try {
            $name = $this->getEntityMeta($entityClassName)->getName();
            $this->em->createQuery("DELETE {$name} p")->execute();
        } catch (Throwable $e) {
            throw $this->convertToStorageException($e);
        }
    }

    /**
     * Выполняет запрос на вставку/обновление накопленного кэша записей.
     *
     * @param bool $force
     */
    protected function checkAndFlushUpsert(bool $force = false): void
    {
        foreach ($this->upsertData as $entityName => $entities) {
            if ($force || \count($entities) >= $this->batchCount) {
                try {
                    $this->upsertEntities($entityName, $entities);
                } catch (Throwable $e) {
                    throw $this->convertToStorageException($e);
                }
                unset($this->upsertData[$entityName]);
            }
        }
    }

    /**
     * Обновляет список сущностей одного типа.
     *
     * @param string   $entityName
     * @param object[] $entities
     */
    protected function upsertEntities(string $entityName, array $entities): void
    {
        $meta = $this->getEntityMeta($entityName);
        $idName = $meta->getSingleIdentifierFieldName();

        $doctrineEntities = $this->em->createQueryBuilder()
            ->select('e')
            ->from($entityName, 'e')
            ->andWhere("e.{$idName} IN (:ids)")
            ->setParameter('ids', array_keys($entities))
            ->getQuery()
            ->execute()
        ;

        $doctrineEntitiesById = [];
        foreach ($doctrineEntities as $doctrineEntity) {
            $id = $meta->getFieldValue($doctrineEntity, $idName);
            $doctrineEntitiesById[$id] = $doctrineEntity;
        }

        foreach ($entities as $id => $entity) {
            if (isset($doctrineEntitiesById[$id])) {
                $this->fillEntityFromOther($doctrineEntitiesById[$id], $entity);
                $this->em->flush();
                $this->em->detach($doctrineEntitiesById[$id]);
                unset($doctrineEntitiesById[$id]);
            } else {
                $this->insert($entity);
            }
        }
    }

    /**
     * Наполняет первый объект данными, хранящимися во втором.
     *
     * @param object $first
     * @param object $second
     */
    protected function fillEntityFromOther(object $first, object $second): void
    {
        $metaFirst = $this->getEntityMeta($first);
        $metaSecond = $this->getEntityMeta($second);
        $fieldNames = $metaFirst->getFieldNames();
        foreach ($fieldNames as $fieldName) {
            $secondValue = $metaSecond->getFieldValue($second, $fieldName);
            $metaFirst->setFieldValue($first, $fieldName, $secondValue);
        }
    }

    /**
     * Возвращает массив первичных ключей для объекта.
     *
     * @param object $entity
     *
     * @return array
     */
    protected function getIdentifiersFromEntity(object $entity): array
    {
        return $this->getEntityMeta($entity)->getIdentifierValues($entity);
    }

    /**
     * Возвращает мета описание сущноссти из Doctrine.
     *
     * @param object|string $entity
     *
     * @return ClassMetadata
     */
    protected function getEntityMeta(object|string $entity): ClassMetadata
    {
        if (\is_object($entity)) {
            $entity = $this->getEntityName($entity);
        }

        try {
            $meta = $this->em->getClassMetadata($entity);
        } catch (Throwable $e) {
            throw $this->convertToStorageException($e);
        }

        return $meta;
    }

    /**
     * Возвращает имя сущности для указанного объекта.
     *
     * @param object $entity
     *
     * @return string
     * @psalm-return class-string
     */
    protected function getEntityName(object $entity): string
    {
        return \get_class($entity);
    }

    /**
     * Преобразует указанное исключение к типу исключения хранилища.
     *
     * @param Throwable $e
     *
     * @return StorageException
     */
    protected function convertToStorageException(Throwable $e): StorageException
    {
        return new StorageException($e->getMessage(), 0, $e);
    }
}
