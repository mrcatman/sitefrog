<?php

namespace Sitefrog\View\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sitefrog\Facades\AssetManager;
use Sitefrog\Facades\FormManager;
use Sitefrog\Facades\LayoutManager;
use Sitefrog\Facades\PageData;
use Sitefrog\View\Components\Form\Form;
use Sitefrog\View\HTMX;

class BaseController extends Controller
{

    public function __construct(

    )
    {
        $this->initialize();
    }

    protected function initialize() {}

    protected function handleHtmxFormRequest()
    {
        $form = FormManager::get(request()->form());
        if (!$form) {
            throw new \Exception("Form not found: ".request()->form());
        }
        if (request()->redirectUrl()) {
            return redirect(request()->redirectUrl());
        }

        return (new Form(
            form: $form,
        ))->tryRender();
    }

    protected function render(string $view, array $view_params = [])
    {
        if (HTMX::isFormRequest()) {
            return $this->handleHtmxFormRequest();
        }

        if (request()->redirectUrl()) {
            return redirect(request()->redirectUrl());
        }

        $params = [];
        $layout_view = LayoutManager::getForRequest(request());

        PageData::setView($view);
        PageData::setParams($view_params);

        if (HTMX::isHtmxRequest() && !HTMX::isModalRequest() && !HTMX::isFullReloadRequest()) {
            return view($view, $view_params);
        }

        if (HTMX::isModalRequest()) {
            $layout_view = 'sitefrog::layouts.modal-content';
            $params['__sf_modal'] = request()->modal();
        }

        if (Str::endsWith($layout_view, '.json')) {
            AssetManager::addCss(Storage::disk('sitefrog')->path('resources/css/grid/index.scss'), 'sitefrog_grid_css');

            $params['__sf_grid_file'] = $layout_view;
            $layout_view = 'sitefrog::layouts.grid';
        }

        return view($layout_view, $params);
    }

    protected function renderGrid($layout) {
        return $this->render('sitefrog::grid', [
            'layout' => $layout
        ]);
    }


}
