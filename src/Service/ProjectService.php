<?php

namespace App\Service;

class ProjectService {

    public function get_projects() : array {
        return [
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
        ];
    }

    public function get_project(int $id) : array {
        return [
            [
                'id' => $id,
                'name' => 'Formation Symfony',
                'status' => 'in_progress',
            ]
        ];
    }
}