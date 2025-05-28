<?php
namespace Sitefrog\Permissions;

enum DefaultRoles: int
{
    case GUEST = 1;
    case BLOCKED = 2;
    case USER = 3;
    case SUPERADMIN = 255;

    public static function ids()
    {
        return array_map(function($role) {
            return $role->value;
        }, self::cases());
    }
}
