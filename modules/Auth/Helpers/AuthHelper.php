<?php

namespace Modules\Auth\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Sitefrog\Facades\FormManager;
use Sitefrog\Facades\RedirectManager;
use Sitefrog\Models\User;
use Sitefrog\View\Form\Fields\Input;
use Sitefrog\View\Form\Form;

class AuthHelper
{

    public static function getLoginForm(string $name): Form
    {
        $loginForm = new Form(
            $name,
            [
                new Input(
                    name: 'username',
                    label: __('sitefrog.auth::fields.username'),
                    validationRules: ['required']
                ),
                (new Input(
                    name: 'password',
                    label: __('sitefrog.auth::fields.password'),
                    validationRules: ['required']
                ))->setType('password'),
            ]
        );
        $loginForm->onSubmit(fn() => self::login($loginForm));
        FormManager::register($loginForm);

        return $loginForm;
    }

    public static function getRegisterForm(string $name): Form
    {
        $registerForm = new Form(
            $name,
            [
                new Input(
                    name: 'username',
                    label: __('sitefrog.auth::fields.username'),
                    validationRules: ['required']
                ),
                (new Input(
                    name: 'password',
                    label: __('sitefrog.auth::fields.password'),
                    validationRules: ['required', 'confirmed']
                ))->setType('password'),
                (new Input(
                    name: 'password_confirmation',
                    label: __('sitefrog.auth::fields.password_confirmation'),
                    validationRules: ['required', config('sitefrog.auth.passwords.rules')]
                ))->setType('password'),
            ]
        );

        if (config('sitefrog.auth.emails.enable')) {
            $registerForm->getFields()->prepend(
                (new Input(
                    name: 'email',
                    label: __('sitefrog.auth::fields.email'),
                    validationRules: ['required', 'email']
                ))->setType('email'),
            );
        }

        $registerForm->onSubmit(fn() => self::register($registerForm));
        FormManager::register($registerForm);

        return $registerForm;
    }


    public static function login(Form $form)
    {
        $data = $form->getData();
        if (!Auth::attempt(['username' => $data['username'], 'password' => $data['password']])) { // todo: email auth
            return $form->setErrors('username', [__('sitefrog.auth::messages.wrong_credentials')]);
        }
        request()->session()->regenerate();

        $redirect = request()->input('redirect', sitefrogRoute('auth', 'register'));
        RedirectManager::set($redirect);
    }

    public static function register(Form $form)
    {
        $data = $form->getData();
        if (User::where(['username' => $data['username']])->count()) {
            return $form->setErrors('username', [__('sitefrog.auth::messages.username_taken')]);
        }
        if (config('sitefrog.auth.emails.enable')) {
            if (User::where(['email' => $data['email']])->count()) {
                return $form->setErrors('email', [__('sitefrog.auth::messages.email_taken')]);
            }
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();
        Auth::login($user);

        RedirectManager::set(sitefrogRoute('auth', 'profile'));
    }

}
