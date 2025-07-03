<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'quality_id',
    ];
    protected $table = 'colors';
    protected $connection = 'mysql';
}
