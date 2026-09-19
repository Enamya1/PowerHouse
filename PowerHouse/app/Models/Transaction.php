<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'transaction_uuid',
    'user_id',
    'membership_id',
    'membership_type_id',
    'staff_id',
    'amount',
    'currency',
    'transaction_type',
    'payment_method',
    'payment_gateway',
    'gateway_transaction_id',
    'status',
    'description',
    'notes',
    'transaction_date',
])]
class Transaction extends Model
{
    protected $casts = [
        'amount'          => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class, 'membership_type_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
