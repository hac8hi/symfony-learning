<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[] projects with their owner and tasks loaded in a single query
     */
    public function findAllWithTasks(): array
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u', 't')
            ->innerJoin('p.User', 'u')
            ->leftJoin('p.tasks', 't')
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneWithTasks(int $id): ?Project
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u', 't')
            ->innerJoin('p.User', 'u')
            ->leftJoin('p.tasks', 't')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
