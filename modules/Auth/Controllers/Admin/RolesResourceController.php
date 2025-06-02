<?php

namespace Modules\Auth\Controllers\Admin;
use Illuminate\Database\Eloquent\Builder;
use Sitefrog\Permissions\PermissionManager;
use Sitefrog\View\Components\Form\Fields\Checkbox;
use Sitefrog\View\Components\Form\Fields\FormGroup;
use Sitefrog\View\Components\Form\Fields\Input;
use Sitefrog\View\Controllers\AdminResourceController;
use Sitefrog\View\Form\Form;
use Sitefrog\View\Table\Column;
use Sitefrog\View\Table\Table;
use Spatie\Permission\Models\Role;

class RolesResourceController extends AdminResourceController {

    protected $resource = Role::class;
    protected $url = 'roles';

    protected $sortable = ['id', 'name'];

    protected $searchable = ['name'];

    public function initialize() {
        parent::initialize();
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

        $form = new Form(
            fields: [
                new Input(
                    name: 'name',
                    label: __('sitefrog.auth::fields.role_name'),
                    rules: ['required']
                ),
            ]
        );

        if ($item) {
            $manager = app()->make(PermissionManager::class); // todo: move into FormBuilder
            foreach ($manager->getAll() as $group) {
                $fields = [];
                foreach ($group['permissions'] as $permission) {
                    $name = 'permissions.'.str_replace('.','-', $permission['full_name']);
                    $fields[] = new Checkbox(
                        name: $name,
                        value: $item->hasPermissionTo($permission['full_name']),
                        label: __($permission['label'])
                    );
                }
                $form->addField(
                    new FormGroup(
                        label: $group['label'],
                        fields: $fields
                    ),
                );
            }
        }

        return $form;
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
