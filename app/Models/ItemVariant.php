<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'unit_label',
        'unit_qty',
        'color',
        'size',
        'price',
        'stock_quantity',
        'sku',
    ];

        
public function item()
{
    return $this->belongsTo(Item::class);
}

}



