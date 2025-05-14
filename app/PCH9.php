<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PCH9 extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'PCH9';

     public function odpo()
    {
        return $this->belongsTo(ODPO::class, 'BaseAbs', 'DocEntry');
    }

}
