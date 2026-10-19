<?php
// src/EventListener/TaskListener.php
namespace App\EventListener;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, entity: Task::class)]
class TaskListener
{
    public function prePersist(Task $task, PrePersistEventArgs $args): void
    {
        if ($task->getKeyTask()) {
            return;
        }

        $project = $task->getProject();
        $max = 0;
        foreach ($project->getTasks() as $other) {
            if (preg_match('/-(\d+)$/', (string) $other->getKeyTask(), $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        $task->setKeyTask($project->getKeyProject() . '-' . ($max + 1));
    }
}