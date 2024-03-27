<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coffee_species',
        'age',
        'location',
        'batch_number',
        'Date_Set',
        'Schedule_Type',
        'progress_status',
        
    ];

    protected $dates = ['Date_Set'];
}
