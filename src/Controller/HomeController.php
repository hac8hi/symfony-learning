<?php

    namespace App\Controller;

    use App\Service\ProjectService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class HomeController extends AbstractController {

        public function __construct(
            private readonly ProjectService $project_service,
        ) {}

        #[Route('/', name: 'app_home', methods: ['GET'])]
        public function index(): Response {
            return $this->render('home/index.html.twig', [
                'projects' => $this->project_service->get_projects(),
            ]);
        }
    }
