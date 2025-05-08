<?php

namespace Sitefrog\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider {

    public $htmxTriggers = [];
    public $redirectUrl = null;

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot(): void {
        $this->registerMacros();
    }


    private function loadHelpers()
    {
        require_once base_path('src/Helpers/functions.php');
    }

    private function registerMacros()
    {
        $self = $this;

        Request::macro('modal', function() {
            return $this->input('_sf_modal');
        });
        Request::macro('form', function() {
            return $this->input('_sf_form');
        });

        Request::macro('referer', function () {
            $referer = parse_url($this->headers->get('referer'));
            return $referer['scheme'] . '://' . $referer['host'] . $referer['path'];
        });

        Request::macro('redirectUrl', function () use ($self) {
            return $self->redirectUrl;
        });

        Request::macro('setRedirectUrl', function ($redirectUrl) use ($self) {
            $self->redirectUrl = $redirectUrl;
        });

        Request::macro('htmxTrigger', function ($event, $data = []) use ($self) {
            $self->htmxTriggers[$event] = $data;
        });

        Request::macro('getHtmxTriggers', function () use ($self) {
            return $self->htmxTriggers;
        });

        Request::macro('closeModal', function () {
            return $this->htmxTrigger('sitefrog:close-modal', ['modal' => request()->modal()]);
        });
    }

}
