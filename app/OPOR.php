<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPOR extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'OPOR';

     public function por1()
    {
        return $this->hasMany(POR1::class, 'DocEntry', 'DocEntry');
    }

    public function grpos()
    {
        return $this->hasManyThrough(
            OPDN::class,
            PDN1::class,
            'BaseEntry',  
            'DocEntry',   
            'DocEntry',   
            'DocEntry'    
        ); 
    }
    public function DeductionLines()
     {
        return $this->hasMany(POR1::class, 'DocEntry', 'DocEntry');
     }
}
