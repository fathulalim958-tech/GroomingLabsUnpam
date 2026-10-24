<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'avatar',
        'is_active',
        'is_member',
        'member_discount',
        'member_discount_type',
        'member_discount_quota',
        'member_discount_remaining',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_member' => 'boolean',
            'member_discount' => 'float',
            'member_discount_quota' => 'integer',
            'member_discount_remaining' => 'integer',
        ];
    }

    /**
     * Check if user has active member discount quota.
     */
    public function hasActiveMemberDiscount(): bool
    {
        return $this->is_member && $this->member_discount_remaining > 0 && $this->member_discount > 0;
    }

    /**
     * Calculate discount for a given price.
     */
    public function calculateDiscount(float $originalPrice): float
    {
        if (! $this->hasActiveMemberDiscount()) {
            return 0;
        }

        if ($this->member_discount_type === 'percent') {
            return min($originalPrice, round(($originalPrice * $this->member_discount) / 100));
        }

        return min($originalPrice, (float) $this->member_discount);
    }

    /**
     * Get formatted member discount label.
     */
    public function getMemberDiscountLabelAttribute(): string
    {
        if ($this->member_discount_type === 'percent') {
            return intval($this->member_discount) . '%';
        }

        return 'Rp ' . number_format($this->member_discount, 0, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get bookings made by this user (as customer).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get bookings handled by this user (as cashier).
     */
    public function cashierBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'cashier_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a kasir (cashier).
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    /**
     * Check if user is a pelanggan (customer).
     */
    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the avatar URL or a default placeholder.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff';
    }
}
