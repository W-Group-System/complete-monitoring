<?php
namespace App;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $connection = 'system1_db';
    protected $guard_name = 'web';
}
