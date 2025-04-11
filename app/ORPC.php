<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ORPC extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'ORPC';

     public function creditNoteLines()
     {
          return $this->hasMany(Rpc1::class, 'DocEntry', 'DocEntry');
     }
}
