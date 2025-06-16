<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_code','code');
    }

    public function child() : HasMany
    {
        return $this->hasMany(Region::class,'parent_code', 'code');
    }

    public function scopeProvince($query)
    {
        return $query->where('type', 'province');
    }

    public function scopeRegencies($query)
    {
        return $query->where('type', 'regency');
    }

    public function scopeDistrict($query)
    {
        return $query->where('type', 'district');
    }

    public function scopeVillage($query)
    {
        return $query->where('type', 'village');
    }
}
