<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\AddrObj as LiquetsoftEntity;

#[ORM\Entity]
final class AddrObj extends LiquetsoftEntity
{
}
