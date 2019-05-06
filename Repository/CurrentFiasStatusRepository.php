<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\FiasBundle\Repository;

use Liquetsoft\Fias\Symfony\FiasBundle\Entity\CurrentFiasStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Репозиторий для статусов установки/обновления.
 *
 * @method CurrentFiasStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrentFiasStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrentFiasStatus[]    findAll()
 * @method CurrentFiasStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrentFiasStatusRepository extends ServiceEntityRepository
{
    /**
     * @inheritdoc
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CurrentFiasStatus::class);
    }

    /**
     * Возвращает последнюю версию ФИАС, которая была загружена в БД или null,
     * если успешных загрузок не было.
     *
     * @return CurrentFiasStatus|null
     */
    public function lastSucceededVersion(): ?CurrentFiasStatus
    {
        $queryBuilder = $this->createQueryBuilder('t');

        $queryBuilder->where('t.isCompleted = true');
        $queryBuilder->orderBy('t.createdAt', 'DESC');
        $queryBuilder->setMaxResults(1);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
