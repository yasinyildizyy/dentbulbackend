<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FaqGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FaqGroup>
 *
 * @method FaqGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqGroup[]    findAll()
 * @method FaqGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqGroup::class);
    }
//    /**
//     * @return FaqGroup[] Returns an array of FaqGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FaqGroup
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
