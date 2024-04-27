<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    protected $fillable = ['schedule_id', 'coffee_type', 'kilos_harvested'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}