<?php

namespace App\Repository;

use App\Entity\RessourcePedagogique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RessourcePedagogique|null find($id, $lockMode = null, $lockVersion = null)
 * @method RessourcePedagogique|null findOneBy(array $criteria, array $orderBy = null)
 * @method RessourcePedagogique[]    findAll()
 * @method RessourcePedagogique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourcePedagogiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RessourcePedagogique::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(RessourcePedagogique $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RessourcePedagogique $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return RessourcePedagogique[] Returns an array of RessourcePedagogique objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RessourcePedagogique
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
