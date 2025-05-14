<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POR1 extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'POR1';

     public function grpoLines()
    {
        return $this->hasMany(PDN1::class, 'BaseEntry', 'DocEntry')
            ->whereColumn('BaseLine', 'LineNum'); 
    }

    public function header()
    {
        return $this->belongsTo(ODPO::class, 'DocEntry', 'DocEntry');
    }

}
