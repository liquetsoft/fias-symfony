<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager;

use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponseBase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use InvalidArgumentException;

/**
 * Объект, который сохраняет текущую версию ФИАС с помощью doctrine.
 */
class DoctrineVersionManager implements VersionManager
{
    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * @param ManagerRegistry $doctrine
     * @param string          $entityClassName
     *
     * @throws InvalidArgumentException
     */
    public function __construct(ManagerRegistry $doctrine, string $entityClassName)
    {
        $this->em = $doctrine->getManager();

        $trimmedEntityClassName = trim($entityClassName, " \t\n\r\0\x0B\\");
        if (!is_subclass_of($trimmedEntityClassName, FiasVersion::class)) {
            throw new InvalidArgumentException(
                "Entity class must be a child of '" . FiasVersion::class . "' class."
            );
        }

        $this->entityClassName = $trimmedEntityClassName;
    }

    /**
     * @inheritdoc
     */
    public function setCurrentVersion(InformerResponse $info): VersionManager
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurrentVersion(): InformerResponse
    {
        $response = new InformerResponseBase;

        return $response;
    }
}
