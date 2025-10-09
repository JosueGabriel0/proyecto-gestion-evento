<?php

namespace App\Application\UseCases\Role;

use App\Domain\Repositories\RoleRepository;

class SearchRolesUseCase
{
    private $repo;
    public function __construct(RoleRepository $repo) { $this->repo = $repo; }

    public function execute(string $term, int $perPage = 10)
    {
        return $this->repo->searchRoles($term, $perPage);
    }
}