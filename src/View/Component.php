<?php
namespace Sitefrog\View;

use Illuminate\View\View;
use Sitefrog\View\Components\Error;

class Component
{
    public static function getTemplate(): string {
        throw new \Exception('Template not set');
    }

    public function beforeRender(): void {}

    public function render(): View | string
    {
        $this->beforeRender();
        return view($this->getTemplate(), [
            'this' => $this
        ]);
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
