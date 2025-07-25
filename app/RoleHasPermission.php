<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
    protected $connection = 'system1_db';
    public $timestamps = false;
}
