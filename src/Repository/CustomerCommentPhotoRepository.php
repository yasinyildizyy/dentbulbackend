<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CustomerCommentPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerCommentPhoto>
 *
 * @method CustomerCommentPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerCommentPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerCommentPhoto[]    findAll()
 * @method CustomerCommentPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerCommentPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerCommentPhoto::class);
    }
//    /**
//     * @return CustomerCommentPhoto[] Returns an array of CustomerCommentPhoto objects
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

//    public function findOneBySomeField($value): ?CustomerCommentPhoto
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
