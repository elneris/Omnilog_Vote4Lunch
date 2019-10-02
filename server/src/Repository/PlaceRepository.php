<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }

    public function findByCoordonate($neLat, $neLng, $swLat, $swLng)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.lat BETWEEN :nelat AND :swlat')
            ->andWhere('p.lng BETWEEN :nelng AND :swlng')
            ->setParameter('nelat', $neLat)
            ->setParameter('nelng', $neLng)
            ->setParameter('swlat', $swLat)
            ->setParameter('swlng', $swLng)
            ->getQuery()
            ->getResult()
            ;
    }
}
