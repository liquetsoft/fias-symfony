<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities;

use Doctrine\ORM\Mapping as ORM;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion;

/**
 * Сущность для тестов менеджера версий с использованием doctrine.
 *
 * @ORM\Entity
 */
class VersionManagerTestMockEntity extends FiasVersion
{
}
