<?php

namespace App\Models;

use App\Models\Cashier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'item_image',
        'item_price',
        'item_stock',
        'expiry_date',
        'item_description',
        'coffee_type',
        'branch',
        'production_date',
        'transfer_date',
        'arrival_date',
        'requested_by',
        'transfer_status',
        // Add other fields as needed
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'purchase_id', 'id');
    }

  

}

