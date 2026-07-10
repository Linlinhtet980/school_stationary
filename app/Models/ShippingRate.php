<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_name',
        'base_fee',
        'extra_fee_per_item'
    ];
}
