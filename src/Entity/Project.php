<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'project')]
#[ORM\UniqueConstraint(name: 'UNIQ_CONSTRAINT_EMAIL', fields: ['key_ref'])]
#[UniqueEntity(fields: ['key_ref'], message: 'There is already a project that has this key_ref')]
class Project {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $name = null;

    #[ORM\Column(length: 3)]
    private ?string $key_ref = null;

   

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;
}