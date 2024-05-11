<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\Availability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Availability>
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availability::class);
    }


     // Méthode pour récupérer les disponibilités d'un véhicule spécifique
    public function findByVehicle(Vehicle $vehicle)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.vehicle = :vehicle')
            ->setParameter('vehicle', $vehicle)
            ->getQuery()
            ->getResult();
    }

    public function findByAvailability($duration, $price_per_day)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.start_date <= :start_date')
            ->setParameter('start_date', $duration['start_date'])
            ->andWhere('a.end_date >= :end_date')
            ->setParameter('end_date', $duration['end_date'])
            ->andWhere('a.price_per_day <= :maxPrice')
            ->setParameter('maxPrice', $price_per_day)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Availability[] Returns an array of Availability objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Availability
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
