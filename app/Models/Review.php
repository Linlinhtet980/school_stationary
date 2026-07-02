<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'rating',
        'comment',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(Customer::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
