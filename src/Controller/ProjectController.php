<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Attribute\Route;

    use App\Service\ProjectService;
    use App\Service\TaskService;

    class ProjectController extends AbstractController {

        public function __construct(
            private readonly ProjectService $project_service,
            private readonly TaskService $task_service,
        ) {}

        #[Route('api/get/projects', name: 'api_get_projects_index', methods: ['GET'])]
        public function index(): JsonResponse {
            return new JsonResponse([
                'data' => array_map($this->project_service->to_array(...), $this->project_service->get_projects()),
            ]);
        }

        #[Route('api/get/project/{id}', name: 'api_get_project', methods: ['GET'])]
        public function one(int $id): JsonResponse {
            $project = $this->project_service->get_project($id);
            if ($project === null) {
                return new JsonResponse(['error' => 'Project not found.'], JsonResponse::HTTP_NOT_FOUND);
            }

            return new JsonResponse([
                'data' => [
                    ...$this->project_service->to_array($project),
                    'tasks' => array_map($this->task_service->to_array(...), $project->getTasks()->toArray()),
                ],
            ]);
        }
    }
