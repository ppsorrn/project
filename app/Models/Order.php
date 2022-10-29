<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['phone', 'shipping_address'];

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function type()
    {
        return $this->belongsTo(Type::class);
    }
}
