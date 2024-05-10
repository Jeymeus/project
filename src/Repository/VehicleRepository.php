<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

     /**
     * Retourne un véhicule en fonction de son slug.
     *
     * @param string $slug
     * @return Vehicle|null
     */
    public function findOneBySlug(string $slug): ?Vehicle
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * Retourne tous les véhicules disponibles.
     *
     * @return Vehicle[]
     */
    public function findAllAvailableVehicles(): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.availability', 'a')
            ->andWhere('a.status = :status')
            ->setParameter('status', true) // Supposons que true signifie disponible
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Vehicle[] Returns an array of Vehicle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicle
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
