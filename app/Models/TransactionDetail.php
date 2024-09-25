<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    public function transactionHeader()
    {
        return $this->belongsTo(TransactionHeader::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
