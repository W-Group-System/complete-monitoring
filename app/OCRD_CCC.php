<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OCRD_CCC extends Model
{
    protected $connection = 'sqlsrv_ccc';
    protected $table = 'OCRD';

    public function opdn()
    {
        return $this->hasMany(OPDN_CCC::class, 'CardName', 'CardName');
    }
}
