<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     *
     * @param string $slug The slug of the vehicle to find.
     * @return Vehicle|null The vehicle with the specified slug, or null if no such vehicle is found.
     */
    public function findOneBySlug(string $slug): ?Vehicle
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @return Vehicle[]
     * @return array<Vehicle>
     */
    public function findAllAvailableVehicles(): array
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.availability', 'a')
            ->andWhere('a.status = :status')
            ->setParameter('status', true)
            ->getQuery()
            ->getResult();
    }


}
