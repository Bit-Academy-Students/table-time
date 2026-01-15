<?php

namespace App\Products\ProductsRepository;

/**
 * Bestandsnaam: ProductsRepository.php
 *
 * Beschrijving:
    * Dit bestand bevat de ProductsRepository klasse die verantwoordelijk is voor het beheren van Products-entiteiten in de applicatie.
    * De repository biedt methoden voor het opslaan en verwijderen van Products via Doctrine ORM.
 *
 * Auteur: Keano Broekman
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.2
 * - Laatste wijziging: 19 November 2025
 * - Beheer: Git
 */

use App\Products\ProductsEntity\ProductsEntity as Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Product $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush(); 
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
