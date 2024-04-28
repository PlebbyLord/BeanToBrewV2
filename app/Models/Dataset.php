<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;
    protected $fillable = ['sales_date', 'coffee_type', 'coffee_form', 'sales_kg', 'price_per_kilo'];
}
