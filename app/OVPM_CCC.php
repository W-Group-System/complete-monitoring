<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OVPM_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'OVPM';

     public function paymentLines()
     {
          return $this->hasMany(Vpm2_CCC::class, 'DocEntry', 'DocEntry');
     }
}
