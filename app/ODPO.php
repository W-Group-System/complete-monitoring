<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ODPO extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'ODPO';

     public function apDownPaymentLines()
     {
        return $this->hasMany(DPO1::class, 'DocEntry', 'DocEntry'); 
     }
}
