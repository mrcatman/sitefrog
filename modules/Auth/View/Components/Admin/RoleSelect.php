<?php

namespace Modules\Auth\View\Components\Admin;

use Illuminate\Support\Collection;
use Sitefrog\Models\User;
use Sitefrog\Permissions\DefaultRoles;
use Sitefrog\View\Components\Form\Field;
use Spatie\Permission\Models\Role;

class RoleSelect extends Field
{
    protected ?int $selectedDefaultRoleId;

    protected array | Collection $roleIds;
    protected Collection $customRoleOptions;


    public function __construct(
        protected string $name,
        protected $value = null,
        protected ?array $attrs = [],
        protected ?string $label = null,
        protected ?array $rules = []
    )
    {
        parent::__construct(
            name: $name,
            value: $value,
            label: $label,
            attrs: $attrs,
            rules: $rules
        );

        $this->customRoleOptions = Role::whereNotIn('id', DefaultRoles::ids())->pluck('name', 'id');
    }

    /** @var User $user */
    public function setValues(mixed $user)
    {
        if (isset($user['default_role_id']) || isset($user['role_ids'])) {
            $this->selectedDefaultRoleId = isset($user['default_role_id']) ? $user['default_role_id'] : DefaultRoles::USER->value;
            $this->roleIds = isset($user['role_ids']) ? $user['role_ids'] : [];
            return;
        }
        $userRoleIds = $user->roles->map(function($role) {
            return $role->id;
        });
        $this->selectedDefaultRoleId = count($userRoleIds) > 0 ? $userRoleIds->intersect(collect(DefaultRoles::ids()))->first() : DefaultRoles::USER->value;
        $this->roleIds = $userRoleIds;
    }

    public static function getTemplate(): string
    {
        return 'sitefrog.auth::components.admin.role-select';
    }

}
