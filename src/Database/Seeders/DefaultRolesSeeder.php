<?php

namespace Sitefrog\Database\Seeders;

use Illuminate\Database\Seeder;
use Sitefrog\Permissions\DefaultRoles;
use Spatie\Permission\Models\Role;

class DefaultRolesSeeder extends Seeder
{

    public function run(): void
    {
        $roles = [
            DefaultRoles::GUEST->value => 'guest',
            DefaultRoles::BLOCKED->value => 'blocked',
            DefaultRoles::USER->value => 'user',
            DefaultRoles::UNCONFIRMED->value => 'unconfirmed',
            DefaultRoles::SUPERADMIN->value => 'superadmin'
        ];
        foreach ($roles as $role_id => $role_name) {
            $role = Role::find($role_id);
            if (!$role) {
                $role = new Role([
                    'id' => $role_id
                ]);
            }

            $role->name = $role_name;
            $role->save();

            echo 'Added role: '.$role_name.PHP_EOL;
        }
    }
}
