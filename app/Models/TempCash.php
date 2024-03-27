<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCash extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purchase_id',
        'item_name',
        'item_image',
        'item_price',
        'quantity',
    ];
}
