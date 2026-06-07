<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
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
}
