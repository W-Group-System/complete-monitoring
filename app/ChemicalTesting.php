<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChemicalTesting extends Model
{
    protected $fillable = [
        'quality_id',
        'parameter',
        'specification',
        'result',
        'remarks',
    ];
    protected $table = 'chemical_testings';
    protected $connection = 'mysql';
}
