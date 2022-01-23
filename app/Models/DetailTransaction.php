<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_id', 'user_id', 'product_id', 'order_number', 'quantity', 'price', 'total'];
}
