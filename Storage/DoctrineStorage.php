<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use Throwable;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 */
class DoctrineStorage implements Storage
{
    protected EntityManager $em;

    protected int $insertBatch = 0;

    protected int $insertCount = 0;

    protected array $supportedClasses = [];

    public function __construct(EntityManager $em, int $insertBatch = 1000)
    {
        $this->em = $em;
        $this->insertBatch = $insertBatch;
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
        try {
            $this->em->flush();
            $this->em->clear();
        } catch (Throwable $e) {
            throw new StorageException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports(object $entity): bool
    {
        return $this->supportsClass(get_class($entity));
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass(string $class): bool
    {
        $trimmedClass = trim($class, '\\');

        if (!isset($this->supportedClasses[$trimmedClass])) {
            try {
                $this->em->getClassMetadata($trimmedClass);
                $this->supportedClasses[$trimmedClass] = true;
            } catch (Throwable $e) {
                $this->supportedClasses[$trimmedClass] = false;
            }
        }

        return $this->supportedClasses[$trimmedClass];
    }

    /**
     * {@inheritDoc}
     */
    public function insert(object $entity): void
    {
        try {
            $this->em->persist($entity);
            ++$this->insertCount;
            if ($this->insertCount === $this->insertBatch) {
                $this->insertCount = 0;
                $this->em->flush();
                $this->em->clear();
            }
        } catch (Throwable $e) {
            throw new StorageException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(object $entity): void
    {
        try {
            $mergedEntity = $this->mergeEntityToDoctrine($entity);
            $this->em->remove($mergedEntity);
            $this->em->flush();
            $this->em->clear();
        } catch (Throwable $e) {
            throw new StorageException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function upsert(object $entity): void
    {
        try {
            $this->mergeEntityToDoctrine($entity);
            $this->em->flush();
            $this->em->clear();
        } catch (Throwable $e) {
            throw new StorageException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function truncate(string $entityClassName): void
    {
        $meta = $this->em->getClassMetadata($entityClassName);
        $name = $meta->getName();
        $this->em->createQuery("DELETE {$name} p")->execute();
    }

    /**
     * Добавляет сущность инициированную в сериализаторе в контекст doctrine.
     *
     * @param object $entity
     *
     * @return object
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     *
     * @psalm-suppress DocblockTypeContradiction
     */
    private function mergeEntityToDoctrine(object $entity): object
    {
        $mergedEntity = null;
        $className = get_class($entity);
        $identifiers = $this->getIdentifiersFromEntity($entity);
        $entityFromDoctrine = $this->em->find($className, $identifiers);

        if ($entityFromDoctrine) {
            $mergedEntity = $this->mergeEntities($entityFromDoctrine, $entity);
        } else {
            $this->em->persist($entity);
            $mergedEntity = $entity;
        }

        return $mergedEntity;
    }

    /**
     * Возвращает массив первичных ключей для сущности.
     *
     * @param object $entity
     *
     * @return array
     */
    private function getIdentifiersFromEntity(object $entity): array
    {
        $className = get_class($entity);
        $meta = $this->em->getClassMetadata($className);
        $identifiers = $meta->getIdentifierValues($entity);

        return $identifiers;
    }

    /**
     * Переносит значения из второй сущности в первую.
     *
     * @param object $first
     * @param object $second
     *
     * @return object
     */
    private function mergeEntities(object $first, object $second): object
    {
        $classNameFirst = get_class($first);
        $metaFirst = $this->em->getClassMetadata($classNameFirst);
        $classNameSecond = get_class($second);
        $metaSecond = $this->em->getClassMetadata($classNameSecond);

        $fieldNames = $metaFirst->getFieldNames();
        foreach ($fieldNames as $fieldName) {
            $secondValue = $metaSecond->getFieldValue($second, $fieldName);
            $metaFirst->setFieldValue($first, $fieldName, $secondValue);
        }

        return $first;
    }
}
