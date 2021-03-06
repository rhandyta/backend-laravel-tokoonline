<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_transaction', 'status_transaction', 'code_transaction'];


    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function DetailTransaction()
    {
        return $this->hasOne(DetailTransaction::class);
    }
}
