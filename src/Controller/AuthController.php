<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Service\UserRegistrationService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Security\Http\Attribute\CurrentUser;

    class AuthController extends AbstractController {

        #[Route('api/register', name: 'api_register', methods: ['POST'])]
        public function register(Request $request, UserRegistrationService $registration): JsonResponse {
            $payload = $request->toArray();
            $result = $registration->register(
                (string) ($payload['email'] ?? ''),
                (string) ($payload['password'] ?? ''),
            );

            if (is_array($result)) {
                return new JsonResponse(['errors' => $result], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return new JsonResponse([
                'data' => [
                    'id' => $result->getId(),
                    'email' => $result->getEmail(),
                ],
            ], Response::HTTP_CREATED);
        }

        // Handled by the json_login authenticator; only reached when credentials are missing.
        #[Route('api/login', name: 'api_login', methods: ['POST'])]
        public function login(): JsonResponse {
            return new JsonResponse(['error' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        #[Route('api/me', name: 'api_me', methods: ['GET'])]
        public function me(#[CurrentUser] User $user): JsonResponse {
            return new JsonResponse([
                'data' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                ],
            ]);
        }
    }
