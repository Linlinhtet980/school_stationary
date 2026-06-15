<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = [
        'name',
        'description',
        'bundle_price',
        'image',
        'status',
    ];

    public function bundleItems()
    {
        return $this->hasMany(BundleItem::class);
    }
}
