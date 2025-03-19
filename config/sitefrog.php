<?php

use Illuminate\Validation\Rules\Password;
use Sitefrog\Http\Middleware\LoadWidgets;
use Sitefrog\Http\Middleware\SetContext;
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
    'contexts' => [
        'default' => 'main',
        'list' => [
            'main' => [
                'default' => true,
                'theme' => 'Default',

                'css' => [
                    ['source' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css']
                ],
                'js' => [
                    ['source' => 'https://unpkg.com/htmx.org@2.0.4', 'params' => ['integrity' => 'sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+', 'crossorigin' => 'anonymous']]
                ]
            ],
            'admin' => [
                'routes_prefix' => '/admin/',
                'theme' => 'Admin',
                'middleware' => [
                    \Sitefrog\Http\Middleware\CheckGroup::class.':'.\Sitefrog\Models\UserGroup::$ROLE_ID_ADMIN
                ],
                'css' => [
                   // ['source' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css']
                ],
                'js' => [
                    ['source' => 'https://unpkg.com/htmx.org@2.0.4', 'params' => ['integrity' => 'sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+', 'crossorigin' => 'anonymous']]
                ]
            ],
        ]
    ]
];
