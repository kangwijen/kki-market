<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dob', 'email', 'password', 'balance', 'verified'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
