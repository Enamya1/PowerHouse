<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipType extends Model
{
    protected $table = 'membership_type';

    protected $fillable = [
        'membership_type',
        'price',
        'description',
        'duration_days',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price'         => 'decimal:2',
            'duration_days' => 'integer',
            'status'        => 'boolean',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'membership_type_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'membership_type_id');
    }
}
