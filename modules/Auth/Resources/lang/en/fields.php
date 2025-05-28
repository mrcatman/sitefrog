<?php
return [
    'email' => 'E-mail',
    'username' => 'Username',
    'password' => 'Password',
    'password_confirmation' => 'Repeat password',
    'role_name' => 'Role name',
    'common' => 'Common',
    'permissions' => 'Permissions',
    'roles' => [
        'label' => 'Select roles...',
        'user' => [
            'label' => 'Default user role'
        ],
        'superadmin' => [
            'label' => 'Superadmin',
            'description' => 'Gives full permissions, so be careful'
        ],
        'blocked' => [
            'label' => 'Blocked',
            'description' => 'Block the user'
        ],
        'custom' => [
            'label' => 'Select custom roles...',
        ]
    ]
];
