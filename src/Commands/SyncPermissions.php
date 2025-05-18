<?php

namespace Sitefrog\Commands;

use Illuminate\Console\Command;
use Sitefrog\Models\User;
use Sitefrog\Permissions\DefaultRoles;
use Sitefrog\Permissions\PermissionManager;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncPermissions extends Command
{
    public function __construct(
        private PermissionManager $permissionManager
    )
    {
        parent::__construct();
    }

    protected $signature = 'sitefrog:permissions:sync';

    protected $description = 'Synchronize permissions with database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = Role::whereIn('id', DefaultRoles::cases())->get()->keyBy('id');

        $permission_groups = $this->permissionManager->getAll();
        foreach ($permission_groups as $group) {
            foreach ($group['permissions'] as $permission) {
                Permission::findOrCreate($permission['full_name']);

                $defaults = $permission['defaults'];
                if ($defaults === true) {
                    $defaults = DefaultRoles::cases();
                }
                foreach ($defaults as $role) {
                    $roles[$role->value]->givePermissionTo($permission['full_name']);
                }
            }
        }
    }
}
