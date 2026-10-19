<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return Task[] tasks with their project and assignee loaded in a single query
     */
    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('t')
            ->addSelect('p', 'u')
            ->innerJoin('t.Project', 'p')
            ->innerJoin('t.user', 'u')
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Task[]
     */
    public function findByProjectId(int $project_id): array
    {
        return $this->createQueryBuilder('t')
            ->addSelect('u')
            ->innerJoin('t.user', 'u')
            ->andWhere('t.Project = :project_id')
            ->setParameter('project_id', $project_id)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countByUser(User $user): int
    {
        return (int) $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
