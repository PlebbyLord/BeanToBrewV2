<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    
    protected $fillable = ['cart_id', 'name', 'address', 'number', 'shipping_option', 'payment_option', 'total_payment'];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

}
