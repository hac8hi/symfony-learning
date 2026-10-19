<?php

namespace App\Twig;

use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Attribute\AsTwigFunction;

class TaskExtension
{
    public function __construct(
        private TaskRepository $tasks,
        private Security $security,
    ) {}

    #[AsTwigFunction('my_task_count')]
    public function myTaskCount(): int
    {
        $user = $this->security->getUser();

        return $user ? $this->tasks->countByUser($user) : 0;
    }
}