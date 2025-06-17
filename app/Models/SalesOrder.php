<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    protected $guarded = [];

    protected $with = ['items'];

    protected $casts = [
        'payment_payload' => 'json'
    ];

    public function items() : HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }
}
