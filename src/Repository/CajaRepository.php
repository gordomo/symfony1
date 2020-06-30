<?php

namespace App\Repository;

use App\Entity\Caja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Caja|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caja|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caja[]    findAll()
 * @method Caja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CajaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caja::class);
    }

    // /**
    //  * @return Caja[] Returns an array of Caja objects
    //  */

    public function getIngresos($desc = '', $desde = '', $hasta = '')
    {
        $query = $this->createQueryBuilder('c');
        if($desc != '') {
            $query = $query->andWhere('c.descrip like  :descrip')->setParameter('descrip','%'. $desc .'%');
        }
        if($desde != '') {

            $from = new \DateTime($desde->format("Y-m-d")." 00:00:00");
            $to   = new \DateTime($hasta->format("Y-m-d")." 23:59:59");
            $query = $query->andWhere('c.fecha BETWEEN  :from AND :to')
                ->setParameter('from', $from )
                ->setParameter('to', $to);
        }
        $query = $query->select('SUM(c.ingreso)');
        $query = $query->getQuery();
        return $query->getResult();
    }
    public function findByDesc($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.descrip like  :val')
            ->setParameter('val','%'. $value .'%')
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ;
    }
    public function findByCustom($desc = '', $desde = '', $hasta = '')
    {
        $query = $this->createQueryBuilder('c');
        if($desc != '') {
            $query = $query->andWhere('c.descrip like  :descrip')->setParameter('descrip','%'. $desc .'%');
        }
        if($desde != '') {

            $from = new \DateTime($desde->format("Y-m-d")." 00:00:00");
            $to   = new \DateTime($hasta->format("Y-m-d")." 23:59:59");
            $query = $query->andWhere('c.fecha BETWEEN  :from AND :to')
                ->setParameter('from', $from )
                ->setParameter('to', $to);
        }


        return $query
            ->orderBy('c.id', 'DESC')
            ->getQuery();
    }

    public function buscarTodos()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT c.id, c.ingreso, c.egreso, c.llevaTicket, c.fecha, c.descrip FROM App:Caja as c
            ');
    }

    /*
    public function findOneBySomeField($value): ?Caja
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
