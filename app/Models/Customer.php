<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'is_member',
        'member_discount',
        'member_discount_type',
        'member_discount_quota',
        'member_discount_remaining',
        'notes',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_member' => false,
        'member_discount' => 0,
        'member_discount_type' => 'percent',
        'member_discount_quota' => 0,
        'member_discount_remaining' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_member' => 'boolean',
            'member_discount' => 'float',
            'member_discount_quota' => 'integer',
            'member_discount_remaining' => 'integer',
        ];
    }

    /**
     * Get the associated user account (if registered).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get bookings made for this customer.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if customer has active member discount quota.
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
            return intval($this->member_discount).'%';
        }

        return 'Rp '.number_format($this->member_discount, 0, ',', '.');
    }
}
