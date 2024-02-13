<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id', 'item_image', 'item_name', 'item_price', 'quantity', 'delivery_status', 'checkout_status',
    'expiry_date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'item_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class);
    }
}




