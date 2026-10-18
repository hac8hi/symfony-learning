<?php
// src/EventListener/ProjectListener.php
namespace App\EventListener;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, entity: Project::class)]
class ProjectListener
{
    public function prePersist(Project $project, PrePersistEventArgs $args): void
    {
        $project->setKeyProject(strtoupper($project->getKeyProject()));

        $i = 1;
        foreach ($project->getTasks() as $task) {
            if (!$task->getKeyTask()) {
                $task->setKeyTask($project->getKeyProject() . '-' . $i);
            }
            $i++;
        }
    }
}