<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // Which columns can be mass assigned
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    // Rating belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Rating belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
