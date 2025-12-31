<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   

    protected $fillable = [
        'name', 'details'
    ]; 

    protected $casts = [
        'details' => 'json'    
    ];
}

