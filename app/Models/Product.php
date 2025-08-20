<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ✅ Add category_id and subcategory_id here
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',           
        'color',
        'image',
        'image_1',
        'image_2',
        'image_3',
        'category_id',
        'subcategory_id',
        'youtube_link',
        'user_id',
    ];

    // ✅ Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function ratings()
{
    return $this->hasMany(Rating::class);
}
public function getAverageRatingAttribute()
{
    return $this->ratings()->avg('rating');
}

public function users()
{
    return $this->belongsTo(User::class);
}


}
