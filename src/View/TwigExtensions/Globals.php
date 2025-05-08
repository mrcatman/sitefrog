<?php

namespace Sitefrog\View\TwigExtensions;

use Sitefrog\Facades\Context;
use Sitefrog\Facades\Page;
use Sitefrog\View\HTMX;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Globals extends AbstractExtension
{

    public function getName()
    {
        return 'Sitefrog_Extension_Globals';
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('page_title', [$this, 'pageTitle'], [ 'is_safe' => ['all']]),
            new TwigFunction('page_title_full', [$this, 'pageTitleFull'], [ 'is_safe' => ['all']]),

            new TwigFunction('context', [$this, 'context'], [ 'is_safe' => ['all']]),

            new TwigFunction('is_htmx_request', [$this, 'isHtmxRequest'], [ 'is_safe' => ['all']]),
            new TwigFunction('is_form_request', [$this, 'isFormRequest'], [ 'is_safe' => ['all']]),
            new TwigFunction('is_modal_request', [$this, 'isModalRequest'], [ 'is_safe' => ['all']]),
            new TwigFunction('body_attributes', [$this, 'bodyAttributes'], [ 'is_safe' => ['all']]),

        ];
    }

    public function context(): string
    {
        return Context::current();
    }


    public function pageTitle(): string
    {
        return Page::getTitle();
    }

    public function pageTitleFull(): string
    {
        return Page::getFullTitle();
    }

    public function isHtmxRequest(): bool
    {
        return HTMX::isHtmxRequest();
    }
    public function isFormRequest(): bool
    {
        return HTMX::isFormRequest();
    }

    public function isModalRequest(): bool
    {
        return HTMX::isModalRequest();
    }

    public function bodyAttributes()
    {
        return HTMX::bodyAttributes();
    }

}
