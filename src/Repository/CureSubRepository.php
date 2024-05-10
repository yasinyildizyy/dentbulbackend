<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CureSub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CureSub>
 *
 * @method CureSub|null find($id, $lockMode = null, $lockVersion = null)
 * @method CureSub|null findOneBy(array $criteria, array $orderBy = null)
 * @method CureSub[]    findAll()
 * @method CureSub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CureSubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CureSub::class);
    }
//    /**
//     * @return CureSub[] Returns an array of CureSub objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CureSub
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
