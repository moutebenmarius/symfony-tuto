<?php

namespace App\Repository;

use App\Entity\ParametresCompteEleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParametresCompteEleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParametresCompteEleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParametresCompteEleve[]    findAll()
 * @method ParametresCompteEleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParametresCompteEleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParametresCompteEleve::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ParametresCompteEleve $entity, bool $flush = true): void
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
    public function remove(ParametresCompteEleve $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return ParametresCompteEleve[] Returns an array of ParametresCompteEleve objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParametresCompteEleve
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getLatestParameters(){
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();;
    }

   /* public function verifyDateEleveBetweenDeAndA($dateElevet){
        return $this->createQueryBuilder('p')
            ->where(':dateEleve BETWEEN p.de AND p.a')
            ->setParameter(':dateEleve', $dateElevet)
            ->getQuery()
            ->getResult();;
    }*/
}
