<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Storage\Storage;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 */
class DoctrineStorage implements Storage
{
    /**
     * @var ObjectManager
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
    public function __construct(ManagerRegistry $doctrine, int $insertBatch = 800)
    {
        $this->em = $doctrine->getManager();
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
        $this->em->flush();
        $this->em->clear();
    }

    /**
     * @inheritdoc
     */
    public function insert(object $entity): void
    {
        $this->em->persist($entity);
        ++$this->insertCount;

        if ($this->insertCount === $this->insertBatch) {
            $this->insertCount = 0;
            $this->em->flush();
            $this->em->clear();
        }
    }

    /**
     * @inheritdoc
     */
    public function delete(object $entity): void
    {
        $mergedEntity = $this->em->merge($entity);
        $this->em->remove($mergedEntity);
        $this->em->flush();
        $this->em->clear();
    }

    /**
     * @inheritdoc
     */
    public function upsert(object $entity): void
    {
        $this->em->merge($entity);
        $this->em->flush();
        $this->em->clear();
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
}
