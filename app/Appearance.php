<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appearance extends Model
{
    protected $fillable = [
        'quality_id',
    ];
    protected $table = 'appearances';
    protected $connection = 'mysql';
}
