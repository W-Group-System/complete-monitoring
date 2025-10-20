<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PCH9_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'PCH9';

     public function odpo()
    {
        return $this->belongsTo(ODPO_CCC::class, 'BaseAbs', 'DocEntry');
    }

}
