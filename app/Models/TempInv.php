<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempInv extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purchase_id',
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
        'quantity',
        'coffee_type',
        'branch',
        // Add other fields as needed
    ];
}
