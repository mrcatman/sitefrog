<?php

use Illuminate\Validation\Rules\Password;
use Sitefrog\Http\Middleware\LoadWidgets;
use Sitefrog\Http\Middleware\SetHtmxHeaders;

return [
    'auth' => [
        'passwords' => [
            'rules' => Password::min(8)->mixedCase()->numbers()->symbols()
        ],
        'emails' => [
            'enable' => true,
            'confirm' => false
        ],
        'registration' => [
            'enabled' => true
        ]
    ],
    'middleware' => [
        'web' => [
            LoadWidgets::class,
            SetHtmxHeaders::class,
        ]
    ],
    'admin' => [
        'items_per_page' => 10,
    ],
    'modules' => [
        'system' => ['Auth']
    ],
    'system_routes' => [
        'login' => 'sitefrog.auth::login'
    ],
    'assets' => [
        'css' => [
            ['id' => 'sitefrog_css', 'source' => 'resources/css/index.scss'],
        ],
        'js' => [
            ['id' => 'htmx_js','source' => 'resources/js/htmx.js'],
            ['id' => 'sitefrog_common_js','source' => 'resources/js/common.js'],
            ['id' => 'sitefrog_modals_js','source' => 'resources/js/features/modals.js'],
        ]
    ],
    'htmx' => [
        'boost' => true
    ],
    'contexts' => [
        'default' => 'main',
        'list' => [
            'main' => [
                'default' => true,
                'theme' => 'Default',

                'css' => [
                    ['id' => 'bootstrap_css', 'source' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css']
                ],
                'js' => [ ]
            ],
            'admin' => [
                'routes_prefix' => '/admin/',
                'theme' => 'Admin',
                'middleware' => [
                    \Sitefrog\Http\Middleware\CheckGroup::class.':'.\Sitefrog\Models\UserGroup::$ROLE_ID_ADMIN
                ],
                'css' => [],
                'js' => [],
            ],
        ]
    ]
];
