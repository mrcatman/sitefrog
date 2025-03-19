<?php

namespace Sitefrog\View\TwigExtensions;

use Sitefrog\Facades\ComponentManager;
use Sitefrog\View\Components\Error;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Components extends AbstractExtension
{

    public function getName()
    {
        return 'Sitefrog_Extension_Components';
    }

    public function component($name, $data = [])
    {
        try {
            $component = ComponentManager::makeInstance($name, $data);
            return $component->tryRender();
        } catch (\Exception $e) {
            $errorComponent = new Error(exception: $e);
            return $errorComponent->render();
        }
    }

    public function formField($type, $data = [])
    {
        try {
            $component = ComponentManager::makeInstance('form.'.$type, [
                'name' => $data['name'],
            ]);
            return $component->tryRender();
        } catch (\Exception $e) {
            $errorComponent = new Error(exception: $e);
            return $errorComponent->render();
        }
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('component', [$this, 'component']),
            new TwigFunction('form_field', [$this, 'formField']),
        ];
    }
}
