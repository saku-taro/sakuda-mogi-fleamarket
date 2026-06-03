<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'description',
        'price',
        'status',
        'trading_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function item_images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
}
