<?php

namespace Modules\Auth\Controllers;
use Modules\Auth\Helpers\AuthHelper;
use Sitefrog\Facades\RedirectManager;
use Sitefrog\View\Controllers\BaseController;

class AuthController extends BaseController {

    public function login()
    {
        if (auth()->user()) {
            return redirect(sitefrogRoute('auth', 'profile'));
        }

        $form = AuthHelper::getLoginForm('login');
        return $this->render('sitefrog.auth::pages.login', ['form' => $form]);
    }

    public function register()
    {
        if (auth()->user()) {
            return redirect(sitefrogRoute('auth', 'profile'));
        }

        $form = AuthHelper::getRegisterForm('register');
        return $this->render('sitefrog.auth::pages.register', ['form' => $form]);
    }

    public function profile()
    {
        if (!$user = auth()->user()) {
            return redirect(sitefrogRoute('auth', 'login'));
        }
        return $this->render('sitefrog.auth::pages.profile', [
            'user' => $user
        ]);
    }

    public function logout()
    {
        auth()->logout();
        RedirectManager::set('/');
    }

}
