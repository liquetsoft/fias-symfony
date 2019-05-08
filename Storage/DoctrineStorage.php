<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Liquetsoft\Fias\Component\Storage\Storage;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;

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
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
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
    }

    /**
     * @inheritdoc
     */
    public function insert(object $entity): void
    {
    }

    /**
     * @inheritdoc
     */
    public function delete(object $entity): void
    {
    }

    /**
     * @inheritdoc
     */
    public function upsert(object $entity): void
    {
    }
}
