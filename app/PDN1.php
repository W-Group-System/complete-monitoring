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

     public function pch1()
     {
        return $this->hasMany(PCH1::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
     }
}
