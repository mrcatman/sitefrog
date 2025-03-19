<?php

namespace Modules\Auth\Controllers\Admin;
use Illuminate\Database\Eloquent\Builder;
use Sitefrog\Models\User;
use Sitefrog\View\Controllers\AdminResourceController;
use Sitefrog\View\Form\Fields\Input;
use Sitefrog\View\Form\Form;
use Sitefrog\View\Table\Column;
use Sitefrog\View\Table\Table;

class UsersResourceController extends AdminResourceController {

    protected $resource = User::class;
    protected $url = 'users';

    protected $sortable = ['created_at', 'username', 'email'];

    protected $searchable = ['username', 'email'];

    public function __construct() {
        $this->translations = [

        ];
    }

    protected function buildForm($item = null): Form
    {
        return new Form(
            fields: [
                new Input(
                    name: 'username',
                    label: __('sitefrog.auth::fields.username'),
                    validationRules: ['required']
                ),
                new Input(
                    name: 'email',
                    type: 'email',
                    label: __('sitefrog.auth::fields.email'),
                    validationRules: ['required', 'email']
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
                    label: __('sitefrog.auth::fields.id'),
                    sortable: true,
                ),
                new Column(
                    name: 'username',
                    label: __('sitefrog.auth::fields.username'),
                    sortable: true,
                ),
                new Column(
                    name: 'email',
                    label: __('sitefrog.auth::fields.email'),
                    sortable: true,
                ),
                new Column(
                    name: 'created_at',
                    label: __('sitefrog.auth::fields.created_at'),
                    formatter: function(User $user) {
                        return $user->created_at->format('d.m.Y H:i:s');
                    }
                ),
            ]
        );
    }

}
