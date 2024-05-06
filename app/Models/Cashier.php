<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cashier extends Model
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
    
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
  
}
