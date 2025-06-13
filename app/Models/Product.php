<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'slug',
        'description',
        'stock',
        'price',
        'weight'
    ];
}
