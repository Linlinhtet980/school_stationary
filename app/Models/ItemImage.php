<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $fillable = ['item_id', 'image_path'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
