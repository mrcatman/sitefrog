<?php
namespace Modules\Auth\Repositories;

use Illuminate\Database\Eloquent\Model;
use Sitefrog\Permissions\DefaultRoles;
use Sitefrog\Repositories\Repository;
use Spatie\Permission\Models\Role;

class RolesRepository extends Repository {

    protected string $resource = Role::class;

    public function findByIds(array $ids)
    {
        $this->checkPermissions('index');

        return $this->resource::whereIn('id', $ids)->get();
    }

    public function hasPermissions(string $permission_name, Model $item = null): bool
    {
        if ($permission_name == 'delete' && in_array($item->id, DefaultRoles::ids())) {
            return false;
        }

        $user = auth()->user();
        return $user && $user->hasRole(DefaultRoles::SUPERADMIN->value);
    }

    /** @var Role $item */
    protected function afterSave(Model $item, array $data) {
        if (isset($data['permissions'])) {
            $permissions = array_map(function ($permission) {
                return str_replace('--', '.', $permission);
            }, array_keys($data['permissions']));
            $item->syncPermissions($permissions);
        }
    }

}
