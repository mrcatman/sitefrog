<?php
namespace Modules\Auth\Repositories;

use Illuminate\Database\Eloquent\Model;
use Sitefrog\Models\User;
use Sitefrog\Repositories\Repository;

class UsersRepository extends Repository {

    protected string $resource = User::class;
    protected string $permissions_prefix = 'auth.users';

    public function __construct(
        private RolesRepository $rolesRepository,
    )
    {
    }

    /** @var User $item */
    protected function afterSave(Model $item, array $data) {
        if (isset($data['role_ids'])) {
            $roles = $this->rolesRepository->findByIds($data['role_ids']);
            $item->syncRoles($roles);
        }
    }

}
