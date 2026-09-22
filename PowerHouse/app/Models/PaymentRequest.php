<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'payment_requests';
    protected $fillable = [
        'user_id',
        'membership_type_id',
        'amount',
        'qr_key',
        'status',
        'expires_at',
        'confirmed_at',
        'confirmed_by',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'confirmed_by' => 'integer',
        'status' => 'string',
    ];
}
