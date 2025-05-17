<?php

namespace Modules\Auth\Controllers\Admin;
use Illuminate\Database\Eloquent\Builder;
use Sitefrog\View\Controllers\AdminResourceController;
use Sitefrog\View\Form\Fields\Input;
use Sitefrog\View\Form\Form;
use Sitefrog\View\Table\Column;
use Sitefrog\View\Table\Table;
use Spatie\Permission\Models\Role;

class RolesResourceController extends AdminResourceController {

    protected $resource = Role::class;
    protected $url = 'roles';

    protected $sortable = ['id', 'name'];

    protected $searchable = ['name'];

    public function __construct() {
        parent::__construct();
        $this->setTranslations([
            'list' => [
                'title' => __('sitefrog.auth::admin.roles.list')
            ],
            'form' => [
                'create' => [
                    'title' => __('sitefrog.auth::admin.roles.create')
                ],
                'edit' => [
                    'title' => __('sitefrog.auth::admin.roles.edit')
                ]
            ]
        ]);
    }

    protected function getLabel($item)
    {
        return $item->name;
    }

    protected function buildForm($item = null): Form
    {
        return new Form(
            fields: [
                new Input(
                    name: 'name',
                    label: __('sitefrog.auth::fields.role_name'),
                    rules: ['required']
                ),
            ]
        );
    }

    protected function buildTable(Builder $query): Table
    {
        return new Table(
            $query,
            columns: [
                new Column(
                    name: 'id',
                    label: __('sitefrog::fields.id'),
                    sortable: true,
                ),
                new Column(
                    name: 'name',
                    label: __('sitefrog.auth::fields.role_name'),
                    sortable: true,
                ),
            ]
        );
    }

}
