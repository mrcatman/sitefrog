<?php

namespace Sitefrog\Providers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider {


    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->loadHelpers();
    }


    private function loadHelpers()
    {
        require_once base_path('src/Helpers/functions.php');
    }


}
