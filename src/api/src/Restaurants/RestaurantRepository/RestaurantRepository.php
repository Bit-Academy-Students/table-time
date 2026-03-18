<?php

namespace App\Restaurants\RestaurantRepository;

/**
 * Bestandsnaam: RestaurantsRepository.php
 *
 * Beschrijving:
    * Dit bestand bevat de RestaurantsRepository klasse die verantwoordelijk is voor het beheren van Restaurants-entiteiten in de applicatie.
    * De repository biedt methoden voor het opslaan en verwijderen van Restaurants via Doctrine ORM.
 *
 * Auteur: Keano Broekman
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.2
 * - Laatste wijziging: 14 November 2025
 * - Beheer: Git
 */

use App\Restaurants\RestaurantEntity\RestaurantEntity as Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function save(Restaurant $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Restaurant $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush(); 
    }

    //    /**
    //     * @return Restaurant[] Returns an array of Restaurant objects
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

    //    public function findOneBySomeField($value): ?Restaurant
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
