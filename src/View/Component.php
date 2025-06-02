<?php
namespace Sitefrog\View;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Sitefrog\Exceptions\OverwriteResponseException;
use Sitefrog\Traits\MagicGetSet;
use Sitefrog\View\Components\Error;

class Component
{
    use MagicGetSet;

    public static function getTemplate(): string {
        throw new \Exception('Template not set');
    }

    public function beforeRender(): void {}


    public function getChildren(): array | Collection | null
    {
        return null;
    }

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
        } catch (\Throwable $e) {
            if ($e instanceof OverwriteResponseException) {
                throw $e;
            }
            $errorComponent = new Error(exception: $e);
            return $errorComponent->render();
        }
    }

}
