<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'order_id',
        'transaction_id',
        'sender_email',
        'amount',
        'currency',
        'status',
        'paid_at'
    ];
}
