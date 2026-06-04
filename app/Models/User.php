<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function isSuperAdmin() { return $this->role->name === 'Super Admin'; }
    public function isInventoryManager() { return $this->role->name === 'Inventory Manager'; }
    public function isOrderStaff() { return $this->role->name === 'Order Staff'; }
    public function isCustomerSupport() { return $this->role->name === 'Customer Support'; }
    public function isFinanceManager() { return $this->role->name === 'Finance Manager'; }
    public function isCustomer() { return $this->role->name === 'Customer'; }

    public function isStaff() { 
        return $this->role->name !== 'Customer'; 
    }

    public function hasRole($roleName) {
        return $this->role->name === $roleName;
    }
}
