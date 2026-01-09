<!--
/**
 * Bestandsnaam: MenuRepository.php
 *
 * Beschrijving:
    * Dit bestand bevat de MenuRepository klasse die verantwoordelijk is voor het beheren van Menu-entiteiten in de applicatie.
    * De repository biedt methoden voor het opslaan en verwijderen van Menu's via Doctrine ORM.
 *
 * Auteur: Johan Tol
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.2
 * - Laatste wijziging: 20 November 2025
 * - Beheer: Git
 */
-->

<?php

namespace App\Menus\MenusRepository;

use App\Menus\MenusEntity\MenuEntity as Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function save(Menu $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Menu $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
