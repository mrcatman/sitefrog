<?php

namespace Sitefrog\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    public static $ROLE_ID_USER = 1;
    public static $ROLE_ID_ADMIN = 255;

    protected $fillable = [
        'name',
    ];

}
