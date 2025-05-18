<?php
namespace Sitefrog\Permissions;


class PermissionManager {

    private array $groups = [];

    public function registerResource($group, $labels_prefix, $except = [], $override_defaults = [])
    {
        $translations = trans($labels_prefix);

        foreach (ResourcePermissions::LIST as $permission) {
            $defaults = $override_defaults[$permission['name']] ?? $permission['defaults'];
            if (!in_array($permission['name'], $except)) {

                if (!isset($this->groups[$group])) {
                    $this->registerGroup($group, $labels_prefix.'.group_label');
                }

                $label = isset($translations[$permission['name']])
                    ? $translations[$permission['name']]
                    : __('sitefrog::permissions.'.$permission['name']);

                $this->register(
                    $group,
                    $label,
                    $permission['name'],
                    $defaults
                );
            }
        }
    }

    public function registerGroup($name, $label)
    {
        $this->groups[$name] = [
            'label' => $label,
            'permissions' => collect([])
        ];
    }

    public function register($group, $label, $name, $defaults = [])
    {
        $permission = $this->groups[$group]['permissions']->firstWhere('name', $name);
        if (!$permission) {
            $permission = [
                'name' => $name,
                'full_name' => $group.'.'.$name,
                'label' => $label,
                'defaults' => $defaults
            ];
            $this->groups[$group]['permissions']->push($permission);
        }
    }

    public function getAll()
    {
        return $this->groups;
    }

}
