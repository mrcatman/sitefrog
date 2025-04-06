<?php

namespace Sitefrog\View\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HTMLAttributes extends AbstractExtension
{

    public function getName()
    {
        return 'Sitefrog_Extension_HTMLAttributes';
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('html_attributes', [$this, 'htmlAttributes'], ['pre_escape' => 'html', 'is_safe' => ['html']])
        ];
    }

    public function htmlAttributes(...$attrs): string
    {
        return \Sitefrog\View\HTMLAttributes::process($attrs);
    }



}
