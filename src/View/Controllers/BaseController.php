<?php

namespace Sitefrog\View\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sitefrog\Facades\AssetManager;
use Sitefrog\Facades\FormManager;
use Sitefrog\Facades\LayoutManager;
use Sitefrog\Facades\PageData;
use Sitefrog\Facades\RedirectManager;
use Sitefrog\View\Components;

class BaseController extends Controller
{

    protected function isHtmxRequest()
    {
        return request()->header('hx-request') === 'true';
    }

    protected function isHtmxFormRequest()
    {
        return $this->isHtmxRequest() && request()->has('_sf_form');
    }

    protected function handleHtmxFormRequest()
    {
        $formName = request()->input('_sf_form');
        $form = FormManager::get($formName);
        if (!$form) {
            throw new \Exception("Form not found: $formName");
        }
        if (RedirectManager::get()) {
            return redirect(RedirectManager::get());
        }

        return (new Components\Form\Form(
            form: $form,
            isHtmxRequest: true
        ))->tryRender();
    }

    protected function render(string $view, array $view_params = [])
    {
        if ($this->isHtmxFormRequest()) {
            return $this->handleHtmxFormRequest();
        }

        if (RedirectManager::get()) {
            return redirect(RedirectManager::get());
        }

        $params = [];
        $layout_view = LayoutManager::getForRequest(request());

        if ($this->isHtmxRequest()) {
            return view($view, $view_params);
        }

        PageData::setView($view);
        PageData::setParams($view_params);

        if (Str::endsWith($layout_view, '.json')) {
            AssetManager::addCss(Storage::disk('sitefrog')->path('resources/css/grid/index.scss'));

            $params['__sf_grid_file'] = $layout_view;
            $layout_view = 'sitefrog::layouts.grid';
        }

        return view($layout_view, $params);
    }

}
