<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'phone',
        'reference',
        'merchant_request_id',
        'checkout_request_id',
        'status',
        'mpesa_receipt',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
