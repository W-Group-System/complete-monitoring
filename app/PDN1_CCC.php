<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PDN1_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'PDN1';

    //  public function grpo()
    //  {
    //      return $this->belongsTo(OPDN::class, 'DocEntry', 'DocEntry');
    //  }
     public function purchaseOrder()
     {
        return $this->belongsTo(OPOR_CCC::class, 'BaseEntry', 'DocEntry'); 
     }
     public function purchaseOrderNew()
      {
         return $this->belongsTo(OPOR_CCC::class, 'BaseEntry', 'DocEntry')->where('BaseType', 22);
      }

     public function pch1()
     {
        return $this->hasMany(PCH1_CCC::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
     }

     public function sourcePurchaseOrderLine()
      {
         return $this->belongsTo(POR1_CCC::class, 'BaseLine', 'LineNum')
            ->where('BaseType', 22)
            ->whereColumn('BaseEntry', 'DocEntry');
      }
      
      public function warehouseCode()
      {
         return $this->belongsTo(OWHS_CCC::class, 'WhsCode', 'WhsCode');
      }
}
