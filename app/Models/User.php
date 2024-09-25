<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'user_detail_id', 'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class, 'user_detail_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function transactionsAsBuyer()
    {
        return $this->hasMany(TransactionHeader::class, 'buyer_id');
    }

    public function transactionsAsSeller()
    {
        return $this->hasMany(TransactionHeader::class, 'seller_id');
    }
}
