<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use Ramsey\Uuid\UuidInterface;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 */
class DoctrineStorage implements Storage
{
    /**
     * @var array<string, bool>
     */
    private array $supportedClasses = [];

    /**
     * @var array<string, array<string, object>>
     *
     * @psalm-var array<class-string, array<string, object>>
     */
    private array $upsertData = [];

    public function __construct(
        protected readonly EntityManager $em,
        protected readonly int $batchCount = 1000,
    ) {
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
            } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            throw $this->convertToStorageException($e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function upsert(object $entity): void
    {
        $meta = $this->getEntityMeta($entity);
        $id = (string) $meta->getFieldValue(
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
        } catch (\Throwable $e) {
            throw $this->convertToStorageException($e);
        }
    }

    /**
     * Выполняет запрос на вставку/обновление накопленного кэша записей.
     */
    protected function checkAndFlushUpsert(bool $force = false): void
    {
        foreach ($this->upsertData as $entityName => $entities) {
            if ($force || \count($entities) >= $this->batchCount) {
                try {
                    $this->upsertEntities($entityName, $entities);
                } catch (\Throwable $e) {
                    throw $this->convertToStorageException($e);
                }
                unset($this->upsertData[$entityName]);
            }
        }
    }

    /**
     * Возвращает мета описание сущноссти из Doctrine.
     */
    protected function getEntityMeta(object|string $entity): ClassMetadata
    {
        if (\is_object($entity)) {
            $entity = $this->getEntityName($entity);
        }

        try {
            $meta = $this->em->getClassMetadata($entity);
        } catch (\Throwable $e) {
            throw $this->convertToStorageException($e);
        }

        return $meta;
    }

    /**
     * Пробует преобразовать указанное значение к примитиву.
     */
    protected function convertToPrimitive(mixed $data): mixed
    {
        if (\is_scalar($data) || $data === null) {
            return $data;
        } elseif ($data instanceof \DateTimeInterface) {
            return $data->format('Y-m-d H:i:s');
        } elseif ($data instanceof UuidInterface) {
            return $data->toString();
        } elseif (\is_object($data) && method_exists($data, '__toString')) {
            return (string) $data;
        }

        throw new StorageException("Can't convert value to primitive");
    }

    /**
     * Обновляет список сущностей одного типа.
     *
     * @psalm-param class-string $entityName
     *
     * @param array<string, object> $entities
     */
    private function upsertEntities(string $entityName, array $entities): void
    {
        $meta = $this->getEntityMeta($entityName);
        $idName = $meta->getSingleIdentifierFieldName();

        /** @var object[] */
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
            $id = (string) $meta->getFieldValue($doctrineEntity, $idName);
            $doctrineEntitiesById[$id] = $doctrineEntity;
        }

        foreach ($entities as $id => $entity) {
            if (!isset($doctrineEntitiesById[$id])) {
                $this->insert($entity);
            } elseif (!$this->isEntitiesEqual($doctrineEntitiesById[$id], $entity)) {
                $this->fillEntityFromOther($doctrineEntitiesById[$id], $entity);
                $this->em->flush();
            }
        }

        foreach ($doctrineEntities as $doctrineEntity) {
            $this->em->detach($doctrineEntity);
        }
    }

    /**
     * Возвращает правду, если сущности содержат одинаковые данные.
     *
     * @psalm-suppress MixedMethodCall
     */
    private function isEntitiesEqual(object $first, object $second): bool
    {
        if (\get_class($first) !== \get_class($second)) {
            return false;
        }

        $isEqual = true;
        foreach (get_class_methods($first) as $method) {
            if (!str_starts_with($method, 'get') && !str_starts_with($method, 'is')) {
                continue;
            }
            $firstValue = $this->convertToPrimitive($first->$method());
            $secondValue = $this->convertToPrimitive($second->$method());
            if ($firstValue !== $secondValue) {
                $isEqual = false;
                break;
            }
        }

        return $isEqual;
    }

    /**
     * Наполняет первый объект данными, хранящимися во втором.
     */
    private function fillEntityFromOther(object $first, object $second): void
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
     */
    private function getIdentifiersFromEntity(object $entity): array
    {
        return $this->getEntityMeta($entity)->getIdentifierValues($entity);
    }

    /**
     * Преобразует указанное исключение к типу исключения хранилища.
     */
    private function convertToStorageException(\Throwable $e): StorageException
    {
        return new StorageException($e->getMessage(), 0, $e);
    }

    /**
     * Возвращает имя сущности для указанного объекта.
     *
     * @psalm-return class-string
     */
    private function getEntityName(object $entity): string
    {
        return \get_class($entity);
    }
}
