<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\BalanceHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|BalanceHistory find($id, $lockMode = null, $lockVersion = null)
 * @method null|BalanceHistory findOneBy(array $criteria, array $orderBy = null)
 * @method BalanceHistory[]    findAll()
 * @method BalanceHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BalanceHistory::class);
    }
}
