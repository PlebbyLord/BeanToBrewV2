<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany as RelationsHasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'mobile_number', // Added mobile_number to mass assignable attributes
        'role', // Added role to mass assignable attributes
        'branch',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function purchase(): RelationsHasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function viewitem(): RelationsHasMany
    {
        return $this->hasMany(ViewItem::class);
    }

    public function cart(): RelationsHasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function rating(): RelationsHasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function cashier(): RelationsHasMany
    {
        return $this->hasMany(Cashier::class);
    }

    public function tempcash(): RelationsHasMany
    {
        return $this->hasMany(TempCash::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

}
