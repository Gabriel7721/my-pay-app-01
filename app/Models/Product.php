<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'price',
        'currency',
        'image_url',
        'stock'
    ];

    protected $casts = ['price' => "decimal:2"];
}
