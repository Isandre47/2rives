<?php

namespace App\Repository;

use App\Entity\Emission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Emission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emission[]    findAll()
 * @method Emission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Emission::class);
    }

    /**
    * @return Emission[] Returns an array of Emission objects
    */
    public function allWithCategory()
    {
        return $this->createQueryBuilder('p')
            // p.category refers to the "category" property on product
            ->innerJoin('p.category', 'c')
            // selects all the category data to avoid the query
            ->addSelect('c')
            ->andWhere('p.category = c.id')
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Emission
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
