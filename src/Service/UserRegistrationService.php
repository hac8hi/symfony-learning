<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegistrationService {

    public function __construct(
        private readonly UserPasswordHasherInterface $password_hasher,
        private readonly EntityManagerInterface $entity_manager,
        private readonly ValidatorInterface $validator,
    ) {}

    /**
     * Validates and persists a new user.
     *
     * @return User|list<string> the created user, or the validation error messages
     */
    public function register(string $email, string $password) : User|array {
        $user = (new User())->setEmail($email);

        $errors = $this->validator->validate($email, [new Assert\NotBlank(), new Assert\Email()]);
        $errors->addAll($this->validator->validate($password, [new Assert\NotBlank(), new Assert\Length(min: 8)]));
        $errors->addAll($this->validator->validate($user));

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }

            return $messages;
        }

        $user->setPassword($this->password_hasher->hashPassword($user, $password));
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        return $user;
    }
}
