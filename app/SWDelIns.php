<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SWDelIns extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = '@SW_DEL_INS';
}
