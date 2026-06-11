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

     public function getPriceRangeAttribute()
    {
        // အကယ်၍ variant မရှိပါက 0.00 ပြမည်
        if ($this->variants->isEmpty()) {
            return '0.00 Ks';
        }

        $minPrice = $this->variants->min('price');
        $maxPrice = $this->variants->max('price');

        // စျေးနှုန်း တူညီနေပါက တစ်ခုတည်းပြမည်၊ မတူပါက Range (ဥပမာ - 1,000 - 5,000 Ks) ပြမည်
        if ($minPrice == $maxPrice) {
            return number_format($minPrice, 2) . ' Ks';
        }

        return number_format($minPrice, 2) . ' - ' . number_format($maxPrice, 2) . ' Ks';
    }

    /**
     * Variants အားလုံး၏ Stock အရေအတွက် စုစုပေါင်းကို တွက်ချက်ရန်
     */
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock_quantity');
    }
}

