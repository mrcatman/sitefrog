<?php
namespace Sitefrog\Permissions;

enum DefaultRoles: int
{
    case BLOCKED = 1;
    case USER = 2;
    case SUPERADMIN = 255;
}
