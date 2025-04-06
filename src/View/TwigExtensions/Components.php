<?php

namespace Sitefrog\View\TwigExtensions;

use Sitefrog\Facades\ComponentManager;
use Sitefrog\View\Component;
use Sitefrog\View\Components\Error;
use Sitefrog\View\TwigExtensions\Components\ComponentTokenParser;
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
            return $component->render();
        } catch (\Exception $e) {
            $errorComponent = new Error(exception: $e);
            return $errorComponent->render();
        }
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('component', [$this, 'component'], [ 'is_safe' => ['all']]),
        ];
    }

    public function getTokenParsers(): array
    {
        return [
            new ComponentTokenParser(),
        ];
    }

    public function getComponentContext(string $name, array $data, array $context)
    {
        /** @var $component Component */
        $component = ComponentManager::makeInstance($name, array_merge($context, $data));

        $component->beforeRender();

        return array_merge($context, [
            'this' => $component,
        ]);
    }
}
