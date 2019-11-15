<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Liquetsoft\Fias\Component\Storage\Storage;
use RuntimeException;
use Throwable;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 */
class DoctrineStorage implements Storage
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var int
     */
    protected $insertBatch = 0;

    /**
     * @var int
     */
    protected $insertCount = 0;

    /**
     * @param ManagerRegistry $doctrine
     * @param int             $insertBatch
     */
    public function __construct(ManagerRegistry $doctrine, int $insertBatch = 1000)
    {
        $em = $doctrine->getManager();
        if (!($em instanceof EntityManager)) {
            throw new RuntimeException(
                "Storage can only be used with '" . EntityManager::class . "'"
            );
        }

        $this->em = $em;
        $this->insertBatch = $insertBatch;
    }

    /**
     * @inheritdoc
     */
    public function start(): void
    {
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function truncate(string $entityClassName): void
    {
        $meta = $this->em->getClassMetadata($entityClassName);
        $name = $meta->getName();

        if ($this->em instanceof EntityManager) {
            $this->em->createQuery("DELETE {$name} p")->execute();
        }
    }

    /**
     * Добавляет сущность инициированную в сериализаторе в контекст doctrine.
     *
     * @param object $entity
     *
     * @return object
     *
     * @throws StorageException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
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
     *
     * @throws StorageException
     */
    private function getIdentifiersFromEntity(object $entity): array
    {
        $className = get_class($entity);
        $meta = $this->em->getClassMetadata($className);
        $identifiers = $meta->getIdentifierValues($entity);

        if (empty($identifiers)) {
            throw new StorageException("Can't find identifiers to merge entity.");
        }

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
