<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPDN extends Model
{
     protected $connection = 'sqlsrv';
     protected $table = 'OPDN';

     public function grpoLines()
     {
        return $this->hasMany(PDN1::class, 'DocEntry', 'DocEntry');
     }
     public function purchaseOrders()
     {
        return $this->hasManyThrough(
            OPOR::class,
            PDN1::class,
            'DocEntry',  
            'DocEntry',  
            'DocEntry', 
            'BaseEntry'  
        ); 
     }
     public function apInvoices()
     {
         return $this->hasManyThrough(
             OPCH::class,  
             PCH1::class, 
             'BaseEntry', 
             'DocEntry', 
             'DocEntry',  
             'DocEntry'    
         )->where('PCH1.BaseType', 20);
     }

     public function apDownPayments()
     {
        return $this->hasManyThrough(
            ODPO::class,  
            DPO1::class,  
            'BaseEntry', 
            'DocEntry',   
            'DocEntry',   
            'DocEntry'    
        );
     }

     public function apDownPaymentLines()
     {
        return $this->hasMany(DPO1::class, 'BaseEntry', 'DocEntry');
     }
     public function qualityResult()
     {
        return $this->hasOne(
            SWDelIns::class,
            'U_BatchNum', 
            'NumAtCard'  
        ); 
     }

     public function freightPoInvoice()
     {
        return $this->hasMany(OPOR::class, 'DocNum', 'U_freightPO');
     }
     public function truckingPoInvoice()
     {
        return $this->hasMany(OPOR::class, 'DocNum', 'U_TruckingPO');
     }
     public function truckingPo()
     {
        return $this->hasMany(OPOR::class, 'U_truckingPO', 'DocNum');
     }
}
