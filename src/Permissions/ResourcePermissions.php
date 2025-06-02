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
            'defaults' => [DefaultRoles::USER->value, DefaultRoles::SUPERADMIN->value],
        ],
        [
            'name' => 'edit',
            'defaults' => [DefaultRoles::USER->value, DefaultRoles::SUPERADMIN->value],
        ],
        [
            'name' => 'edit_all',
            'defaults' => [DefaultRoles::SUPERADMIN->value],
        ],
        [
            'name' => 'delete',
            'defaults' => [DefaultRoles::USER->value, DefaultRoles::SUPERADMIN->value],
        ],
        [
            'name' => 'delete_all',
            'defaults' => [DefaultRoles::SUPERADMIN->value],
        ]
    ];

}
