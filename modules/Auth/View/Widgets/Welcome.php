<?php

namespace Modules\Auth\View\Widgets;

use Illuminate\View\View;
use Sitefrog\View\Widget;

class Welcome extends Widget
{

    public function getConfig()
    {
        return [

        ];
    }

    public function render(): View
    {
        $user = auth()->user();
        return view('sitefrog.auth::components.welcome', [
            'user' => $user,
        ]);
    }
}
