<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email','ID_nO','password', 'is_active'])]
#[Hidden(['password', 'remember_token','ID_nO'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function processedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'staff_id');
    }

    protected function casts(): array
    {
        
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ID_nO' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
