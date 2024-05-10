<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Cure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cure>
 *
 * @method Cure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cure[]    findAll()
 * @method Cure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cure::class);
    }
//    /**
//     * @return Cure[] Returns an array of Cure objects
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

//    public function findOneBySomeField($value): ?Cure
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
