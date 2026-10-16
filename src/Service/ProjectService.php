<?php

namespace App\Service;

use App\Entity\Project;
use App\Repository\ProjectRepository;

class ProjectService {

    public function __construct(
        private readonly ProjectRepository $project_repository,
    ) {}

    /**
     * @return Project[]
     */
    public function get_projects() : array {
        return $this->project_repository->findAllWithTasks();
    }

    public function get_project(int $id) : ?Project {
        return $this->project_repository->findOneWithTasks($id);
    }

    /**
     * Plain-array representation, for JSON responses.
     */
    public function to_array(Project $project) : array {
        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'key' => $project->getKeyProject(),
            'description' => $project->getDescription(),
            'owner' => $project->getUser()->getUserIdentifier(),
            'task_count' => $project->getTasks()->count(),
        ];
    }
}
