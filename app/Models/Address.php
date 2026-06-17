<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'address_line',
        'city',
        'phone',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, User::class);
    }
}
