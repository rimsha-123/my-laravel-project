<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'rating'];

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
