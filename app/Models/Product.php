<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code', 'name', 'price', 'description'];

    function shops() 
    {
        return $this->belongsToMany(Shop::class);
    }

    function type() 
    {
        return $this->belongsTo(Type::class);
    }
}
