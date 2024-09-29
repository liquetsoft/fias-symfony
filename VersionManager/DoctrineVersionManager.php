<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformerResponse;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformerResponseFactory;
use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;

/**
 * Объект, который сохраняет текущую версию ФИАС с помощью doctrine.
 */
final class DoctrineVersionManager implements VersionManager
{
    public function __construct(
        private readonly EntityManager $em,
        private readonly string $entityClassName,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrentVersion(FiasInformerResponse $info): void
    {
        $entity = $this->getEntity();
        $entity->setVersion($info->getVersion());
        $entity->setFullurl($info->getFullUrl());
        $entity->setDeltaurl($info->getDeltaUrl());
        $entity->setCreated(new \DateTimeImmutable());

        try {
            $this->em->persist($entity);
            $this->em->flush();
            $this->em->clear();
        } catch (\Throwable $e) {
            throw new \RuntimeException("Can't set new version of FIAS: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentVersion(): ?FiasInformerResponse
    {
        $entity = $this->getEntityRepository()->findOneBy([], ['created' => 'DESC']);
        if ($entity instanceof FiasVersion) {
            return FiasInformerResponseFactory::create(
                $entity->getVersion(),
                $entity->getFullurl(),
                $entity->getDeltaurl()
            );
        }

        return null;
    }

    /**
     * Возвращает сущность Doctrine, которая хранит версии ФИАС.
     */
    private function getEntity(): FiasVersion
    {
        $className = $this->getEntityClassName();

        return new $className();
    }

    /**
     * Возвращает объект репозитория для сущности.
     */
    private function getEntityRepository(): EntityRepository
    {
        $entityClassName = $this->getEntityClassName();

        return $this->em->getRepository($entityClassName);
    }

    /**
     * Возвращает класс сущности для обращения к Doctrine.
     *
     * @psalm-return class-string<FiasVersion>
     */
    private function getEntityClassName(): string
    {
        $trimmedEntityClassName = trim($this->entityClassName, " \t\n\r\0\x0B\\");

        if (!is_subclass_of($trimmedEntityClassName, FiasVersion::class)) {
            $message = \sprintf(
                "Entity class must be a child of '%s' class, got '%s'."
                . " Please check that 'liquetsoft_fias.version_manager_entity' parameter is properly configured",
                FiasVersion::class,
                $trimmedEntityClassName
            );
            throw new \RuntimeException($message);
        }

        return $trimmedEntityClassName;
    }
}
