<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OCRD extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'OCRD';

    public function opdn()
    {
        return $this->hasMany(OPDN::class, 'CardCode', 'CardCode');
    }
}
