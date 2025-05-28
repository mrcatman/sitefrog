<?php

namespace Sitefrog\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Repository {

    protected string $resource;
    protected string $permissions_prefix;


    protected function checkPermissions(string $permission_name, Model $item = null)
    {
       if (!$this->hasPermissions($permission_name, $item)) {
           throw new \Exception("Not authorized");
       }
    }

    public function hasPermissions(string $permission_name, Model $item = null): bool
    {
        $permission = $this->permissions_prefix.'.'.$permission_name;
        $user = auth()->user(); // todo: guests
        if (!$user->can($permission)) {
            return false; // todo: Not authorized page
        }

        return true;
    }

    protected function isOwner(Model $item)
    {
        return auth()->user()->id === $item->user_id;
    }

    protected function beforeFill(Model $item, array $data) {}

    protected function beforeSave(Model $item, array $data) {}

    protected function afterSave(Model $item, array $data) {}

    protected function save(Model $item, array $data)
    {
        $this->beforeFill($item, $data);
        $item->fill($data);

        $this->beforeSave($item, $data);
        $item->save();

        $this->afterSave($item, $data);

        return $item;
    }

    public function index(
        Request $request
    )
    {

    }

    public function get($id)
    {
        $item = $this->resource::find($id);
        $this->checkPermissions('show', $item);

        if (!$item) {
            throw new \Exception("Not found"); // todo: Not found page
        }

        return $item;
    }

    public function create(array $data)
    {
        $this->checkPermissions('create');

        $item = new $this->resource;
        $this->save($item, $data);

        return $item;
    }

    public function edit(Model $item, array $data): Model
    {
        $this->checkPermissions('edit', $item);
        $this->save($item, $data);

        return $item;
    }

    public function delete(Model $item)
    {
        $this->checkPermissions('delete', $item);
        $item->delete();
    }


}
