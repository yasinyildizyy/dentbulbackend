<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PageSub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageSub>
 *
 * @method PageSub|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageSub|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageSub[]    findAll()
 * @method PageSub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageSubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageSub::class);
    }
//    /**
//     * @return PageSub[] Returns an array of PageSub objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PageSub
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
