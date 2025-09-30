<?php
namespace App\Application\UseCases\Role;

use App\Domain\Repositories\RoleRepository;

class GetRolesPaginatedUseCase
{
    private RoleRepository $repo;

    public function __construct(RoleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function execute(int $page = 1, int $perPage = 10)
    {
        return $this->repo->getRolesPaginated($page, $perPage);
    }
}