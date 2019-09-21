<?php

namespace App\Repository;

use App\Entity\Demand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Demand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demand[]    findAll()
 * @method Demand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Demand::class);
    }

    public function findLastDemands($limit){
        return $this->createQueryBuilder('d')
                    ->select('d as demand')
                    ->orderBy('d.id','DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Demand[] Returns an array of Demand objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Demand
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function searchDemandByCategoryProvince($search_province, $search_category){
        $qb = $this->createQueryBuilder('s');

        if($search_category !== ''){
            $qb->leftJoin('s.category', 'category');
            $qb->addSelect('category');
            $qb->andWhere('category.name LIKE :search_category');
            $qb->setParameter('search_category', $search_category);
        }
        if($search_province !== ''){
            $qb->leftJoin('s.province', 'province');
            $qb->addSelect('province');
            $qb->andWhere('province.name LIKE :search_province');
            $qb->setParameter('search_province', $search_province);
        }
        $result= $qb->getQuery()->getResult();
        return $result;
    }
}
