<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PCH1_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'PCH1';

     public function pdn1()
    {
        return $this->belongsTo(PDN1_CCC::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
    }

    public function apInvoice()
    {
        return $this->belongsTo(Opch_CCC::class, 'DocEntry', 'DocEntry');
    }
    public function grpo()
    {
        return $this->belongsTo(Opdn_CCC::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
    }
}
