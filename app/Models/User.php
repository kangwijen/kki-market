<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'role_id',
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

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function transactionsAsBuyer()
    {
        return $this->hasMany(TransactionHeader::class, 'user_id');
    }
}