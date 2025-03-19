<?php

namespace Sitefrog\View\Traits;

use Illuminate\View\View;
use Sitefrog\View\Components\Error;

trait TryRender {

    public function tryRender(): View
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            return (new Error($e))->render();
        }
    }

}
