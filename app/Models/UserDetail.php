<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance', 'verified'];

    protected $attributes = [
        'balance' => 0.00,
        'verified' => false
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
