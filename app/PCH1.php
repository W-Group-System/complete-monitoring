<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PCH1 extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'PCH1';

     public function pdn1()
    {
        return $this->belongsTo(PDN1::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
    }

    public function apInvoice()
    {
        return $this->belongsTo(Opch::class, 'DocEntry', 'DocEntry');
    }
    public function grpo()
    {
        return $this->belongsTo(Opdn::class, 'BaseEntry', 'DocEntry')->where('BaseType', 20);
    }
}
