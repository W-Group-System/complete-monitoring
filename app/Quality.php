<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    protected $fillable = [
        'grpo_no',
        'dr_rr',
        'location_bin',
        'seaweeds',
        'ocular_mc',
        'haghag',
        'agreed_mc',
        'remarks',
        'sda',
        'ice',
        'moss',
    ];
    protected $table = 'qualities';
    protected $connection = 'mysql';
    public function colors()
    {
        return $this->hasOne(Color::class, 'quality_id', 'id');
    }
    public function appearance()
    {
        return $this->hasOne(Appearance::class, 'quality_id', 'id');
    }
    public function chemical_testings()
    {
        return $this->hasMany(ChemicalTesting::class, 'quality_id', 'id');
    }
    public function tie_tie()
    {
        return $this->hasOne(Fom::class, 'quality_id', 'id');
    }
    public function sand()
    {
        return $this->hasOne(Sand::class, 'quality_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
