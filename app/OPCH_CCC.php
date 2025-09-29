<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPCH_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'OPCH';

     public function details()
      {
         return $this->hasMany(Pch1_CCC::class, 'DocEntry', 'DocEntry');
      }

      public function creditNotes()
      {
          return $this->hasManyThrough(
              Orpc_CCC::class,     
              Rpc1_CCC::class,     
              'BaseEntry',     
              'DocEntry',      
              'DocEntry',      
              'DocEntry'       
          )->where('RPC1.BaseType', 18); 
      }
    public function paymentMappings()
    {
        return $this->hasMany(Vpm2_CCC::class, 'DocNum', 'ReceiptNum');
    }
    // public function downPayments()
    // {
    //     return $this->hasMany(ODPO::class, 'NumAtCard', 'NumAtCard');
    // }

    public function pch9()
    {
        return $this->hasMany(PCH9_CCC::class, 'DocEntry', 'DocEntry');
    }
    public function payments()
    {
        return $this->hasManyThrough(
            Ovpm_CCC::class,
            Vpm2_CCC::class,
            'DocEntry',  
            'DocEntry', 
            'DocEntry',  
            'DocNum'     
        );
    }

    // public function apCreditNote()
    // {
    //     return $this->hasOne(ORPC::class, 'NumAtCard', 'NumAtCard')
    //         ->where('NumAtCard', 'LIKE', '%' . $this->NumAtCard . '%');
    // }
    public function apCreditNote()
    {
        return ORPC_CCC::where('NumAtCard', 'LIKE', '%' . $this->NumAtCard . '%')->first();
    }
}
