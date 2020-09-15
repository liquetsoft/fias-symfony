<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponseBase;
use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;
use RuntimeException;
use Throwable;

/**
 * Объект, который сохраняет текущую версию ФИАС с помощью doctrine.
 */
class DoctrineVersionManager implements VersionManager
{
    protected EntityManager $em;

    protected string $entityClassName;

    public function __construct(EntityManager $em, string $entityClassName)
    {
        $this->em = $em;
        $this->entityClassName = $entityClassName;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RuntimeException
     */
    public function setCurrentVersion(InformerResponse $info): VersionManager
    {
        $entity = $this->getEntity();
        $entity->setVersion($info->getVersion());
        $entity->setUrl($info->getUrl());
        $entity->setCreated(new DateTime());

        try {
            $this->em->persist($entity);
            $this->em->flush();
            $this->em->clear();
        } catch (Throwable $e) {
            throw new RuntimeException("Can't set new version of FIAS: {$e->getMessage()}", 0, $e);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function getCurrentVersion(): InformerResponse
    {
        $response = new InformerResponseBase();

        $entity = $this->getEntityRepository()->findOneBy([], ['created' => 'DESC']);
        if ($entity instanceof FiasVersion) {
            $response->setVersion($entity->getVersion());
            $response->setUrl($entity->getUrl());
        }

        return $response;
    }

    /**
     * Возвращает сущность Doctrine, которая хранит версии ФИАС.
     *
     * @return FiasVersion
     *
     * @throws RuntimeException
     *
     * @psalm-suppress InvalidStringClass
     */
    private function getEntity(): FiasVersion
    {
        $className = $this->getEntityClassName();
        $entity = new $className();

        if (!($entity instanceof FiasVersion)) {
            throw new RuntimeException(
                "Entity class must be a child of '" . FiasVersion::class . "' class, got '{$className}'."
                . " Please check that 'liquetsoft_fias.version_manager_entity' parameter is properly configured."
            );
        }

        return $entity;
    }

    /**
     * Возвращает объект репозитория для сущности.
     *
     * @return EntityRepository
     */
    private function getEntityRepository(): EntityRepository
    {
        $entityClassName = $this->getEntityClassName();

        $repo = $this->em->getRepository($entityClassName);
        if (!($repo instanceof EntityRepository)) {
            throw new RuntimeException("Can't find doctrine repository for '{$entityClassName}' entity.");
        }

        return $repo;
    }

    /**
     * Возвращает класс сущности для обращения к Doctrine.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    private function getEntityClassName(): string
    {
        $trimmedEntityClassName = trim($this->entityClassName, " \t\n\r\0\x0B\\");

        if (!is_subclass_of($trimmedEntityClassName, FiasVersion::class)) {
            throw new RuntimeException(
                "Entity class must be a child of '" . FiasVersion::class . "' class, got '{$trimmedEntityClassName}'."
                . " Please check that 'liquetsoft_fias.version_manager_entity' parameter is properly configured."
            );
        }

        return $trimmedEntityClassName;
    }
}
