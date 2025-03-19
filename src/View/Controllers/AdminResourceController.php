<?php

namespace Sitefrog\View\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Sitefrog\Facades\ComponentManager;
use Sitefrog\Facades\FormManager;
use Sitefrog\View\Form\Form;
use Sitefrog\View\Table\Column;
use Sitefrog\View\Table\Table;

class AdminResourceController extends BaseController
{
    protected $resource;
    protected $url;

    protected $sortable = ['id'];
    protected $searchable = [];

    protected $translations = [
        'list' => [
            'heading' => 'List',
        ],
        'form' => [
            'create' => [
                'heading' => 'Create object',
                'button' => 'Add object'
            ],
            'edit' => [
                'heading' => 'Edit object',
                'button' => 'Save object'
            ],
        ]
    ];

    protected function buildForm($item = null): Form {
        throw new \Exception("You need to implement a form");
    }

    protected function buildTable(Builder $query): Table {
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

    protected function getTableActions($item)
    {
        return [
            [
                'label' => __('sitefrog.admin.edit'),
                'url' => $this->getUrl('edit', $item)
            ]
        ];
    }

    protected function getForm($item = null)
    {
        $form = $this->buildForm($item);
        if ($item) {
            $form->setValues($item);
        }

        $form->setName($this->resource.'-edit')
            ->setMethod($item ? 'PUT' : 'POST');

        $form->onSubmit(fn() => $this->submit($form, $item));
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
                        'label' => __('sitefrog.common.actions'),
                        'items' => $this->getTableActions($item)
                    ]);
                }
            ),
        );
        return $table;
    }

    protected function search(Builder $query)
    {
        if (request()->has('search')) {
            $query->where(function($q) {
                foreach ($this->searchable as $field) {
                    $q->orWhere($field, 'LIKE', '%'.request()->input('search').'%');
                }
            });
        }
    }

    public function index()
    {
        $query = $this->resource::query();
        $this->search($query);
        $table = $this->getTable($query);

        return $this->render('sitefrog::pages.admin.list', [
            'table' => $table
        ]);

    }

    public function create()
    {
        $form = $this->getForm();

        return $this->render('sitefrog::pages.admin.form', [
            'form' => $form,
            'item' => null
        ]);
    }

    public function edit(int $id)
    {
        $item = $this->resource::find($id);
        if (!$item) {
            throw new \Exception("Not found"); // todo: Not found page
        }
        $form = $this->getForm($item);

        return $this->render('sitefrog::pages.admin.form', [
            'form' => $form,
            'item' => $item
        ]);
    }

    public function submit(Form $form, $item)
    {
        echo 'test';
    }

}
