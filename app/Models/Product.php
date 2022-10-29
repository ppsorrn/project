<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'price', 'description'];

    function shop() 
    {
        return $this->belongsTo(Shop::class);
    }

    function order()
    {
        return $this->hasOne(Order::class);
    }
}
