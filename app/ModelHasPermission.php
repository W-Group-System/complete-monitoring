<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';
    protected $connection = 'system1_db'; 
    public $timestamps = false;
}
