<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;

class TaskService {

    public function __construct(
        private readonly TaskRepository $task_repository,
    ) {}

    /**
     * @return Task[]
     */
    public function get_tasks() : array {
        return $this->task_repository->findAllWithRelations();
    }

    /**
     * @return Task[]
     */
    public function get_tasks_by_project(int $project_id) : array {
        return $this->task_repository->findByProjectId($project_id);
    }

    /**
     * Plain-array representation, for JSON responses.
     */
    public function to_array(Task $task) : array {
        return [
            'id' => $task->getId(),
            'key' => $task->getKeyTask(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'assignee' => $task->getUser()->getUserIdentifier(),
        ];
    }
}
