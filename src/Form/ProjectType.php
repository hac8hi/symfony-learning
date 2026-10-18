<?php

namespace App\Form;

use App\Entity\Project;
Use App\Form\TaskType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectType extends AbstractType
{
    public function __construct(private Security $security) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('key_project', TextType::class, [
                'label' => 'Reference',
                'required' => true
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'required' => true
            ])
            ->add('tasks', CollectionType::class, [
                'entry_type' => TaskType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)
            {
                $project = $event->getData();
                $user = $this->security->getUser();

                if (!$project->getUser()) 
                {
                    $project->setUser($user);
                };

                foreach ($project->getTasks() as $task)
                {
                    if (!$task->getUser())
                    {
                        $task->setUser($user);
                    };
                }
            });
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
