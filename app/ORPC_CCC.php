<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ORPC_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'ORPC';

     public function creditNoteLines()
     {
          return $this->hasMany(Rpc1_CCC::class, 'DocEntry', 'DocEntry');
     }
}
