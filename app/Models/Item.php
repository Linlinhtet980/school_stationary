<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_id', 
        'brand_id', 
        'name', 
        'description', 
        'price', 
        'stock_quantity', 
        'image', 
        'status'
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ItemVariant::class);
    }

    public function getPriceRangeAttribute()
    {
        if ($this->variants->isEmpty()) {
            return number_format($this->price ?? 0) . ' Ks';
        }

        $minPrice = $this->variants->min('price');
        $maxPrice = $this->variants->max('price');

        if ($minPrice == $maxPrice) {
            return number_format($minPrice) . ' Ks';
        }

        return number_format($minPrice) . ' - ' . number_format($maxPrice) . ' Ks';
    }

    /**
     * Get display price (uses variant min price if item price is null)
     */
    public function getDisplayPriceAttribute()
    {
        if (!is_null($this->price)) {
            return (float) $this->price;
        }

        if ($this->relationLoaded('variants') && $this->variants->isNotEmpty()) {
            return (float) $this->variants->min('price');
        }

        return 0;
    }

    /**
     * Variants အားလုံး၏ Stock အရေအတွက် စုစုပေါင်းကို တွက်ချက်ရန်
     */
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock_quantity');
    }
}

