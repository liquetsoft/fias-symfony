<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\FiasVersion as LiquetsoftEntity;

#[ORM\Entity]
class FiasVersion extends LiquetsoftEntity
{
}
