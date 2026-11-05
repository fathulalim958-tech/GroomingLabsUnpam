<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

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
        'username',
        'email',
        'password',
        'phone',
        'role',
        'service_category',
        'avatar',
        'is_active',
        'work_days',
        'is_member',
        'member_discount',
        'member_discount_type',
        'member_discount_quota',
        'member_discount_remaining',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_active' => true,
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
            'work_days' => 'array',
            'is_member' => 'boolean',
            'member_discount' => 'float',
            'member_discount_quota' => 'integer',
            'member_discount_remaining' => 'integer',
        ];
    }

    /**
     * Indonesian Day Mapping.
     */
    public const DAY_NAMES = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        0 => 'Minggu',
    ];

    /**
     * Check if user (cashier) is available on a specific day of week (0=Minggu, 1=Senin, ..., 6=Sabtu).
     */
    public function isAvailableOnDay(int $dayOfWeek): bool
    {
        if (empty($this->work_days)) {
            return true;
        }

        return in_array($dayOfWeek, array_map('intval', (array) $this->work_days), true);
    }

    /**
     * Check if user (cashier) is scheduled to work today.
     */
    public function isScheduledToday(): bool
    {
        $todayDayOfWeek = \Carbon\Carbon::now('Asia/Jakarta')->dayOfWeek;
        return $this->isAvailableOnDay($todayDayOfWeek);
    }

    /**
     * Get human-readable work schedule string.
     */
    public function getWorkDaysFormattedAttribute(): string
    {
        if (empty($this->work_days)) {
            return 'Setiap Hari (Senin - Minggu)';
        }

        $days = array_map('intval', (array) $this->work_days);
        sort($days);

        if ($days === [1, 2, 3, 4, 5]) {
            return 'Senin - Jumat';
        }

        if ($days === [1, 2, 3, 4, 5, 6]) {
            return 'Senin - Sabtu';
        }

        if (count($days) === 7) {
            return 'Setiap Hari (Senin - Minggu)';
        }

        if ($days === [0, 6] || $days === [6, 0]) {
            return 'Sabtu & Minggu';
        }

        $labels = [];
        $order = [1, 2, 3, 4, 5, 6, 0];
        foreach ($order as $d) {
            if (in_array($d, $days, true)) {
                $labels[] = self::DAY_NAMES[$d];
            }
        }

        return !empty($labels) ? implode(', ', $labels) : 'Tidak Ada Jadwal';
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
            return intval($this->member_discount).'%';
        }

        return 'Rp '.number_format($this->member_discount, 0, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | Booted Model Events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::created(function (User $user) {
            if ($user->role === 'pelanggan' && Schema::hasTable('customers')) {
                Customer::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'is_member' => $user->is_member ?? false,
                        'member_discount' => $user->member_discount ?? 0,
                        'member_discount_type' => $user->member_discount_type ?? 'percent',
                        'member_discount_quota' => $user->member_discount_quota ?? 0,
                        'member_discount_remaining' => $user->member_discount_remaining ?? 0,
                    ]
                );
            }
        });

        static::updated(function (User $user) {
            if ($user->role === 'pelanggan' && Schema::hasTable('customers')) {
                Customer::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'is_member' => $user->is_member ?? false,
                        'member_discount' => $user->member_discount ?? 0,
                        'member_discount_type' => $user->member_discount_type ?? 'percent',
                        'member_discount_quota' => $user->member_discount_quota ?? 0,
                        'member_discount_remaining' => $user->member_discount_remaining ?? 0,
                    ]
                );
            }
        });

        static::deleted(function (User $user) {
            if ($user->role === 'pelanggan' && Schema::hasTable('customers')) {
                Customer::where('user_id', $user->id)->delete();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the customer profile for this user.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

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

    /**
     * Get services this cashier can perform.
     */
    public function services(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_user');
    }

    /**
     * Get label for cashier specialization category.
     */
    public function getServiceCategoryLabelAttribute(): string
    {
        return match ($this->service_category) {
            'mua' => 'MUA',
            default => 'Barberman',
        };
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
            return asset('storage/'.$this->avatar);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=random&color=fff';
    }
}
