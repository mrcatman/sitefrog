<?php
namespace Sitefrog\View;

use Illuminate\View\View;
use Sitefrog\View\Components\Error;

class Component
{
    public function render(): View | string
    {

    }

    public function tryRender(): View | string
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            $errorComponent = new Error(exception: $e);
            return $errorComponent->render();
        }
    }

}
