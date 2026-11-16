<?php

namespace App\Repository;

use App\Entity\Portfolio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Portfolio>
 */
class PortfolioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Portfolio::class);
    }

    public function findByOrdered(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.mockUp', 'm')
            ->leftJoin('p.category', 'c')
            ->andWhere('m.type = :non OR m.id IS NULL')
            ->setParameter('non', 'non')
            ->orderBy('c.categorie', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByName(string $name): ?Portfolio
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom_image = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMockUp(string $mockUpType): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.mockUp', 'm')
            ->andWhere('m.type = :mockUp')
            ->setParameter('mockUp', $mockUpType)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCategorie(string $categorieName): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->leftJoin('p.mockUp', 'm')
            ->andWhere('c.categorie = :categorie')
            ->andWhere('m.type = :non OR m.id IS NULL')
            ->setParameter('non', 'non')
            ->setParameter('categorie', $categorieName)
            ->getQuery()
            ->getResult();
    }

    public function findAllMockup(): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.mockUp', 'm')
            ->leftJoin('p.category', 'c')
            ->andWhere('m.type != :non')
            ->setParameter('non', 'non')
            ->orderBy('c.categorie', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}