<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POR1_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'POR1';

     public function grpoLines()
    {
        return $this->hasMany(PDN1_CCC::class, 'BaseEntry', 'DocEntry')
            ->whereColumn('BaseLine', 'LineNum'); 
    }

    public function header()
    {
        return $this->belongsTo(ODPO_CCC::class, 'DocEntry', 'DocEntry');
    }

}
