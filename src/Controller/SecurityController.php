<?php

    namespace App\Controller;

    use App\Service\UserRegistrationService;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class SecurityController extends AbstractController {

        #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
        public function login(AuthenticationUtils $authentication_utils): Response {
            if ($this->getUser()) {
                return $this->redirectToRoute('app_home');
            }

            return $this->render('security/login.html.twig', [
                'last_username' => $authentication_utils->getLastUsername(),
                'error' => $authentication_utils->getLastAuthenticationError(),
            ]);
        }

        #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
        public function register(Request $request, UserRegistrationService $registration): Response {
            if ($this->getUser()) {
                return $this->redirectToRoute('app_home');
            }

            $email = (string) $request->request->get('email', '');
            $errors = [];

            if ($request->isMethod('POST')) {
                if (!$this->isCsrfTokenValid('register', (string) $request->request->get('_csrf_token'))) {
                    $errors[] = 'Invalid CSRF token.';
                } else {
                    $result = $registration->register($email, (string) $request->request->get('password', ''));
                    if (!is_array($result)) {
                        $this->addFlash('success', 'Account created, you can now log in.');

                        return $this->redirectToRoute('app_login');
                    }
                    $errors = $result;
                }
            }

            return $this->render('security/register.html.twig', [
                'email' => $email,
                'errors' => $errors,
            ]);
        }

        // Intercepted by the firewall's logout listener.
        #[Route('/logout', name: 'app_logout', methods: ['GET'])]
        public function logout(): never {
            throw new \LogicException('This method is intercepted by the logout key on the firewall.');
        }
    }
