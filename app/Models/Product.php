<?php

namespace App\Models;

use App\Models\ProductDetail;
use App\Models\TransactionDetail;
use App\Models\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'img_path',
        'product_type_id',
    ];

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class, 'product_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }
}