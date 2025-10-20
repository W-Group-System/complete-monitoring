<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $connection = 'mysql';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
