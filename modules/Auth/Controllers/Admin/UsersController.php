<?php

namespace Modules\Auth\Controllers\Admin;
use Sitefrog\View\Controllers\BaseController;

class UsersController extends BaseController {

    public function index()
    {
        return $this->render('sitefrog.auth::pages.admin.users.index');
    }

    public function settings()
    {
        return '';
    }

}
