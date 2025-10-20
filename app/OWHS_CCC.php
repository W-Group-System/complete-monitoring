<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OWHS_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'OWHS';
}
