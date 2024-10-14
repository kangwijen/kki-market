<?php

namespace App\Models;

use App\Models\ProductDetail;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'img_path'
    ];

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}