<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SummarySupplier extends Model
{
     public function ocrd()
     {
        return $this->belongsTo(OCRD::class, 'CardCode', 'CardCode');
     }

     public function opdn()
    {
        return $this->hasMany(OPDN::class, 'CardCode', 'CardCode');
    }
}
