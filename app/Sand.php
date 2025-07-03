<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sand extends Model
{
    protected $fillable = [
        'quality_id',
        'foreign_matter',
        'impurities',
        'weight',
        'percent',
        'parts_million',
    ];
    protected $table = 'sands';
    protected $connection = 'mysql';
}
