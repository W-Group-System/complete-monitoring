<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SummarySuppliersCcc extends Model
{
    protected $table = 'summary_suppliers_ccc';
    public function ocrd()
    {
        return $this->belongsTo(OCRD_CCC::class, 'CardName', 'CardName');
    }

    public function opdn()
    {
        return $this->hasMany(OPDN_CCC::class, 'CardName', 'CardName');
    }
}
