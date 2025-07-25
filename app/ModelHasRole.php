<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    protected $table = 'model_has_roles';
    protected $connection = 'system1_db';
    public $timestamps = false;
}
