<?php

namespace Sitefrog\View\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Sitefrog\Facades\AssetManager;
use Sitefrog\Facades\FormManager;
use Sitefrog\Facades\LayoutManager;
use Sitefrog\Facades\Page;
use Sitefrog\Facades\PageData;
use Sitefrog\Facades\RedirectManager;
use Sitefrog\View\Components;
use Sitefrog\View\HTMX;

class BaseController extends Controller
{

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
        ))->tryRender();
    }

    protected function render(string $view, array $view_params = [])
    {
        if (HTMX::isFormRequest()) {
            return $this->handleHtmxFormRequest();
        }

        if (RedirectManager::get()) {
            return redirect(RedirectManager::get());
        }

        $params = [];
        $layout_view = LayoutManager::getForRequest(request());

        PageData::setView($view);
        PageData::setParams($view_params);

        if (HTMX::isHtmxRequest() && !HTMX::isModalRequest()) {
            return view($view, $view_params);
        }

        if (HTMX::isModalRequest()) {
            $layout_view = 'sitefrog::layouts.modal-content';
            $params['__sf_modal_id'] = request()->input('_sf_modal_id');
        }

        if (Str::endsWith($layout_view, '.json')) {
            AssetManager::addCss(Storage::disk('sitefrog')->path('resources/css/grid/index.scss'));

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

    protected function htmxTrigger()
    {

    }

    protected function closeModal()
    {

    }

}
