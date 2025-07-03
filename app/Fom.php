<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fom extends Model
{
    protected $fillable = [
        'quality_id',
    ];
    protected $table = 'foms';
    protected $connection = 'mysql';
}
