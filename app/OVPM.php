<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OVPM extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'OVPM';

     public function paymentLines()
     {
          return $this->hasMany(Vpm2::class, 'DocEntry', 'DocEntry');
     }
}
