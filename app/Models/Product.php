<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'details',
        'price',
        'stock',
        'status',
    ];

    protected $casts = [
        'details' => 'array',
        'price'   => 'decimal:2',
    ];
}