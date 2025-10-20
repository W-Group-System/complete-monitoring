<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PDN1 extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'PDN1';

    //  public function grpo()
    //  {
    //      return $this->belongsTo(OPDN::class, 'DocEntry', 'DocEntry');
    //  }
     public function purchaseOrder()
     {
        return $this->belongsTo(OPOR::class, 'BaseEntry', 'DocEntry'); 
     }
     public function purchaseOrderNew()
      {
         return $this->belongsTo(OPOR::class, 'BaseEntry', 'DocEntry')->where('BaseType', 22);
      }
     public function pch1()
     {
        return $this->hasMany(PCH1::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
     }

     public function sourcePurchaseOrderLine()
      {
         return $this->belongsTo(POR1::class, 'BaseLine', 'LineNum')
            ->where('BaseType', 22)
            ->whereColumn('BaseEntry', 'DocEntry');
      }
}
