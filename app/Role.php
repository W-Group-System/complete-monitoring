<?php 
namespace App;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'system1_db';
    protected $guard_name = 'web';
}
