<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Attribute\Route;

    class ProjectController extends AbstractController {

        #[Route('api/get/projects', name: 'api_get_projects_index', methods: ['GET'])]
        public function index(): JsonResponse {
            return new JsonResponse([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Formation Symfony',
                        'status' => 'in_progress',
                    ],
                    [
                        'id' => 2,
                        'name' => 'API de gestion de tâches',
                        'status' => 'planned',
                    ],
                ],
            ]);
        }
    }
