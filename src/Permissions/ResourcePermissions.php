<?php
namespace Sitefrog\Permissions;

class ResourcePermissions
{
    const LIST = [
        [
            'name' => 'index',
            'defaults' => true,
        ],
        [
            'name' => 'show',
            'defaults' => true,
        ],
        [
            'name' => 'create',
            'defaults' => [DefaultRoles::USER, DefaultRoles::SUPERADMIN],
        ],
        [
            'name' => 'edit',
            'defaults' => [DefaultRoles::USER, DefaultRoles::SUPERADMIN],
        ],
        [
            'name' => 'edit_all',
            'defaults' => [DefaultRoles::SUPERADMIN],
        ],
        [
            'name' => 'delete',
            'defaults' => [DefaultRoles::USER, DefaultRoles::SUPERADMIN],
        ],
        [
            'name' => 'delete_all',
            'defaults' => [DefaultRoles::SUPERADMIN],
        ]
    ];

}
