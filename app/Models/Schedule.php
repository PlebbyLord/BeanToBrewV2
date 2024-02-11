<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'coffee_species',
        'batch_number',
        'Date_Set',
        'Harvest_Date',
        'Schedule_Type',
    ];
}
