<?php

namespace Sitefrog\View\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Sitefrog\Facades\ComponentManager;
use Sitefrog\Facades\FormManager;
use Sitefrog\Facades\Page;
use Sitefrog\View\Components\Admin\FiltersWrapper;
use Sitefrog\View\Components\Box;
use Sitefrog\View\Components\Button;
use Sitefrog\View\Form\Fields\Input;
use Sitefrog\View\Form\Form;
use Sitefrog\View\HTMX;
use Sitefrog\View\Table\Column;
use Sitefrog\View\Table\Table;
use Sitefrog\View\Components\Form\Form as FormComponent;

class AdminResourceController extends BaseController
{
    protected $resource;
    protected $url;

    protected $sortable = ['id'];
    protected $searchable = [];

    protected $translations = [];

    public function __construct()
    {
        $this->translations = [
            'list' => [
                'title' => __('sitefrog::common.list'),
            ],
            'form' => [
                'create' => [
                    'title' => __('sitefrog::common.create'),
                    'button' => __('sitefrog::common.add'),
                ],
                'edit' => [
                    'title' => __('sitefrog::common.edit'),
                    'button' => __('sitefrog::common.save'),
                ],
                'delete' => [
                    'title' => __('sitefrog::common.delete'),
                    'confirm' => __('sitefrog::common.delete_are_you_sure'),
                    'button' => __('sitefrog::common.delete_confirm'),
                ],
            ]
        ];
    }

    protected function setTranslations($translations)
    {
        $this->translations = array_merge($this->translations, $translations);
    }

    protected function buildForm($item = null): Form
    {
        throw new \Exception("You need to implement a form");
    }

    protected function buildTable(Builder $query): Table
    {
        throw new \Exception("You need to implement a table");
    }

    protected function getUrl(string $action, mixed $params = [])
    {
        $name = Route::current()->getName();
        $nameParts = explode('.', $name);
        array_pop($nameParts);
        $nameParts[] = $action;

        return route(implode('.', $nameParts), $params);
    }

    protected function getLabel($item)
    {
        return $item->id;
    }

    protected function getTableActions($item)
    {
        return [
            [
                'content' => __('sitefrog::common.edit'),
                'attrs' => [
                    'href' => $this->getUrl('edit', $item),
                ],
            ],
            [
                'content' => __('sitefrog::common.delete'),
                'attrs' => [
                    'href' => $this->getUrl('delete', $item),
                    'modal' => true
                ],
            ]
        ];
    }

    protected function getEditForm($item = null)
    {
        $form = $this->buildForm($item);
        if ($item) {
            $form->setValues($item);
        }

        $form->setName($this->resource . '-edit')
            ->setMethod($item ? 'PUT' : 'POST');

        $form->setAction($item ? $this->getUrl('edit', $item) : $this->getUrl('create'));
        $form->setSubmitLabel($this->translations['form'][$item ? 'edit' : 'create']['button']);

        $form->onSubmit(fn($form) => $this->submit($form, $item));
        FormManager::register($form);

        return $form;
    }

    protected function getDeleteForm($item = null)
    {
        $form = new Form(
            fields: [
                new Input(
                    name: 'id',
                    type: 'hidden',
                    value: $item->id,
                ),
            ]
        );
        $form->setName($this->resource . '-delete')->setMethod('DELETE');
        if ($item) {
            $form->setValues($item);
        }

        $form->setName($this->resource . '-delete')->setMethod('DELETE');

        $form->setAction($this->getUrl('delete', $item));
        $form->setSubmitLabel($this->translations['form']['delete']['button']);

        $form->onSubmit(fn() => $this->submitDelete($form, $item));
        FormManager::register($form);

        return $form;
    }

    protected function getTable(Builder $query)
    {
        $table = $this->buildTable($query);
        $table->addColumn(
            new Column(
                name: 'actions',
                label: '',
                formatter: function ($item) {
                    return ComponentManager::makeInstance('dropdown', [
                        'title' => __('sitefrog::common.actions'),
                        'items' => $this->getTableActions($item)
                    ])->render();
                }
            ),
        );
        return $table;
    }

    protected function search(Builder $query)
    {
        if (request()->has('search')) {
            $query->where(function ($q) {
                foreach ($this->searchable as $field) {
                    $q->orWhere($field, 'LIKE', '%' . request()->input('search') . '%');
                }
            });
        }
    }

    public function index()
    {
        Page::setTitle($this->translations['list']['title']);

        $query = $this->resource::query();
        $this->search($query);

        $table = $this->getTable($query);

        return $this->renderGrid([
            new Box(
                heading: Page::getTitle(),
                children: [
                    'actions' => [
                        new Button(
                            content: __('sitefrog::common.create'),
                            attrs: [
                                'href' => $this->getUrl('create'),
                                'modal' => true
                            ]
                        ),
                    ],
                    'main' => [
                        new FiltersWrapper(
                            children: [
                                new Input(
                                    name: 'search',
                                    value: request()->input('search'),
                                    attrs: [
                                        'placeholder' => __('sitefrog::common.search'),
                                    ]
                                ),
                                new \Sitefrog\View\Components\Table\Table(
                                    table: $table
                                )
                            ]
                        )
                    ]
                ]
            )
        ]);
    }

    private function renderForm($item = null)
    {
        $formComponent = new FormComponent(
            $this->getEditForm($item)
        );

        if (HTMX::isModalRequest()) {
            return $this->renderGrid([
                $formComponent
            ]);
        }
        return $this->renderGrid([
            new Box(
                heading: Page::getTitle(),
                children: [
                    'main' => [
                        $formComponent
                    ],
                    'actions' => [
                        new Button(
                            content: __('sitefrog::common.back'),
                            attrs: [
                                'href' => $this->getUrl('index')
                            ]
                        ),
                    ]
                ]
            )
        ]);
    }

    public function create()
    {
        Page::setTitle($this->translations['form']['create']['title']);
        return $this->renderForm();

    }

    public function edit(int $id)
    {
        $item = $this->resource::find($id);
        if (!$item) {
            throw new \Exception("Not found"); // todo: Not found page
        }

        Page::setTitle($this->translations['form']['edit']['title']);
        return $this->renderForm($item);
    }

    public function delete($id)
    {
        $item = $this->resource::find($id);
        if (!$item) {
            throw new \Exception("Not found");
        }

        Page::setTitle($this->translations['form']['delete']['title']);

        $formComponent = new FormComponent(
            $this->getDeleteForm($item)
        );

        $content = [
            __($this->translations['form']['delete']['confirm'], ['label' => $this->getLabel($item)]),
            $formComponent,
        ];

        if (HTMX::isModalRequest()) {
            return $this->renderGrid($content);
        }

        return $this->renderGrid([
            new Box(
                heading: $this->translations['form']['delete']['title'],
                children: [
                    'main' => $content,
                    'actions' => [
                        new Button(
                            content: __('sitefrog::common.back'),
                            attrs: [
                                'href' => $this->getUrl('index')
                            ]
                        ),
                    ]
                ]
            )
        ]);
    }

    public function submit(Form $form, $item = null)
    {
        if (!$item) {
            $item = new $this->resource();
        }

        $data = $form->getData();

        $item->fill($data);
        $item->save();

        $this->afterSubmit();
    }

    public function submitDelete(Form $form, $item)
    {
        $item->delete();
        $this->afterSubmit();
    }

    private function afterSubmit()
    {
        if (request()->referer() == $this->getUrl('index')) {
            request()->htmxTrigger('sitefrog:refresh');
        }
        if (request()->modal()) {
            request()->closeModal();
        } else {
            request()->setRedirectUrl($this->getUrl('index'));
        }
    }


}
