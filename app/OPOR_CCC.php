<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPOR_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'OPOR';

     public function por1()
    {
        return $this->hasMany(POR1_CCC::class, 'DocEntry', 'DocEntry');
    }

    public function grpos()
    {
        return $this->hasManyThrough(
            OPDN_CCC::class,
            PDN1_CCC::class,
            'BaseEntry',  
            'DocEntry',   
            'DocEntry',   
            'DocEntry'    
        ); 
    }
    public function DeductionLines()
     {
        return $this->hasMany(POR1_CCC::class, 'DocEntry', 'DocEntry');
     }
     
    //  public function apDownPaymentLines()
    //  {
    //      return $this->hasMany(DPO1::class, 'BaseRef', 'DocNum')
    //                  ->where('BaseType', 22);
    //  }
    public function downpaymentRequests()
    {
        return $this->hasMany(ODPO_CCC::class, 'BaseEntry', 'DocEntry')
                    ->where('BaseType', 22); 
    }
}
