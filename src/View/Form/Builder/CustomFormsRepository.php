<?php
namespace Sitefrog\View\Form\Builder;

use Sitefrog\Models\CustomForm;
use Sitefrog\Repositories\Repository;

class CustomFormsRepository extends Repository {

    protected string $resource = CustomForm::class;

    public function hasPermissions(string $permission_name, $item = null): bool
    {
        if ($permission_name == 'index') {
            return true;
        }

        $user = auth()->user();
        return $user && $user->can('form-builder.forms');
    }

}
