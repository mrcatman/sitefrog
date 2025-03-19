<?php
namespace Sitefrog\Permissions;

use Illuminate\Support\Collection;

class PermissionManager {

    private Collection $groups;

    public function __construct()
    {
        $this->groups = collect([]);
    }

    public function register()
    {

    }

}
