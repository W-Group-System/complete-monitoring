<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ODPO_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'ODPO';

     public function apDownPaymentLines()
     {
        return $this->hasMany(DPO1_CCC::class, 'DocEntry', 'DocEntry'); 
     }
}
