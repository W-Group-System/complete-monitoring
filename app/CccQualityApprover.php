<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CccQualityApprover extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'quality_id',
    ];
    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}