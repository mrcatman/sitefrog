<?php

namespace Sitefrog\Providers;

use Illuminate\Support\ServiceProvider;
use Sitefrog\Commands\AddRole;
use Sitefrog\Commands\SyncPermissions;

class CommandsServiceProvider extends ServiceProvider {

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->commands([
            AddRole::class,
            SyncPermissions::class
        ]);
    }

}
