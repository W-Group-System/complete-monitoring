<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPCH extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'OPCH';

     public function details()
      {
         return $this->hasMany(Pch1::class, 'DocEntry', 'DocEntry');
      }

      public function creditNotes()
      {
          return $this->hasManyThrough(
              Orpc::class,     
              Rpc1::class,     
              'BaseEntry',     
              'DocEntry',      
              'DocEntry',      
              'DocEntry'       
          )->where('RPC1.BaseType', 18); 
      }
    public function paymentMappings()
    {
        return $this->hasMany(Vpm2::class, 'DocNum', 'ReceiptNum');
    }
    // public function downPayments()
    // {
    //     return $this->hasMany(ODPO::class, 'NumAtCard', 'NumAtCard');
    // }

    public function pch9()
    {
        return $this->hasMany(PCH9::class, 'DocEntry', 'DocEntry');
    }
    public function payments()
    {
        return $this->hasManyThrough(
            Ovpm::class,
            Vpm2::class,
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
        return ORPC::where('NumAtCard', 'LIKE', '%' . $this->NumAtCard . '%')->first();
    }
}
