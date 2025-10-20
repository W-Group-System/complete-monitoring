<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPDN_CCC extends Model
{
     protected $connection = 'sqlsrv_ccc';
     protected $table = 'OPDN';

     public function grpoLines()
     {
        return $this->hasMany(PDN1_CCC::class, 'DocEntry', 'DocEntry');
     }
     public function purchaseOrders()
     {
        return $this->hasManyThrough(
            OPOR_CCC::class,
            PDN1_CCC::class,
            'DocEntry',  
            'DocEntry',  
            'DocEntry', 
            'BaseEntry'  
        ); 
     }
     public function apInvoices()
     {
         return $this->hasManyThrough(
             OPCH_CCC::class,  
             PCH1_CCC::class, 
             'BaseEntry', 
             'DocEntry', 
             'DocEntry',  
             'DocEntry'    
         )->where('PCH1.BaseType', 20);
     }

     public function apDownPayments()
     {
        return $this->hasManyThrough(
            ODPO_CCC::class,  
            DPO1_CCC::class,  
            'BaseEntry', 
            'DocEntry',   
            'DocEntry',   
            'DocEntry'    
        );
     }

     public function apDownPaymentLines()
     {
        return $this->hasMany(DPO1_CCC::class, 'BaseEntry', 'DocEntry');
     }

     public function downpaymentLines()
      {
         $poLines = $this->lines()
            ->where('BaseType', 22)
            ->get(['BaseEntry', 'BaseLine']);

         return DPO1_CCC::where('BaseType', 22)
            ->where(function ($query) use ($poLines) {
                  foreach ($poLines as $line) {
                     $query->orWhere(function ($q) use ($line) {
                        $q->where('BaseEntry', $line->BaseEntry)
                           ->where('BaseLine', $line->BaseLine);
                     });
                  }
            })->get();
      }

   //   public function qualityResult()
   //   {
   //      return $this->hasOne(SWDelIns_CCC::class,'U_BatchNum', 'NumAtCard'); 
   //   }

     public function freightPoInvoice()
     {
        return $this->hasMany(OPOR_CCC::class, 'DocNum', 'U_freightPO');
     }
     public function truckingPoInvoice()
     {
        return $this->hasMany(OPOR_CCC::class, 'DocNum', 'U_TruckingPO');
     }
     public function truckingPo()
     {
        return $this->hasMany(OPOR_CCC::class, 'U_truckingPO', 'DocNum');
     }
     public function quality_created()
     {
        return $this->hasOne(Quality::class, 'grpo_no', 'DocNum');
     }
     public function quality_created_approved()
     {
        return $this->hasOne(Quality::class, 'grpo_no', 'DocNum')
                ->where('status', 'Approved');
     }
}
