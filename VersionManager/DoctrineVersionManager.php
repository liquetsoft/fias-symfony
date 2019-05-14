<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\VersionManager;

use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponseBase;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;

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
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
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
