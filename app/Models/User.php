<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ⬅️ add this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ⬅️ add HasApiTokens

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- your relations (no change) ---
    public function ratings() { return $this->hasMany(Rating::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function sellerRequest()
    {
        return $this->hasMany(\App\Models\SellerRequest::class, 'user_id');
    }
    
    }
