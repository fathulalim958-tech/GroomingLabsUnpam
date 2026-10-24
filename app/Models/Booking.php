<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'cashier_id',
        'staff_id',
        'service_category',
        'booking_date',
        'booking_time',
        'queue_number',
        'notes',
        'total_price',
        'discount_amount',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'total_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    /**
     * Status labels in Indonesian.
     *
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        'waiting' => 'Menunggu',
        'called' => 'Dipanggil',
        'in_progress' => 'Sedang Dilayani',
        'skipped' => 'Dilewati',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Tailwind CSS color classes for status badges.
     *
     * @var array<string, string>
     */
    public const STATUS_COLORS = [
        'waiting'     => 'bg-amber-100 text-amber-900 border border-amber-300 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-500/40 whitespace-nowrap inline-block',
        'called'      => 'bg-indigo-100 text-indigo-900 border border-indigo-300 dark:bg-indigo-500/20 dark:text-indigo-300 dark:border-indigo-500/40 whitespace-nowrap inline-block',
        'in_progress' => 'bg-sky-100 text-sky-900 border border-sky-300 dark:bg-sky-500/25 dark:text-sky-300 dark:border-sky-500/40 font-black whitespace-nowrap inline-block',
        'skipped'     => 'bg-purple-100 text-purple-900 border border-purple-300 dark:bg-purple-500/20 dark:text-purple-300 dark:border-purple-500/40 whitespace-nowrap inline-block',
        'completed'   => 'bg-emerald-100 text-emerald-900 border border-emerald-300 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-500/40 whitespace-nowrap inline-block',
        'cancelled'   => 'bg-rose-100 text-rose-900 border border-rose-300 dark:bg-rose-500/20 dark:text-rose-300 dark:border-rose-500/40 whitespace-nowrap inline-block',
    ];

    /**
     * Get customer display name (fallback to registered user name or Walk-in).
     */
    public function getCustomerNameAttribute(): string
    {
        return $this->attributes['customer_name'] ?? $this->user?->name ?? 'Walk-in';
    }

    /**
     * Get customer phone (fallback to registered user phone).
     */
    public function getCustomerPhoneAttribute(): ?string
    {
        return $this->attributes['customer_phone'] ?? $this->user?->phone;
    }

    /**
     * Get formatted booking time without seconds (HH:MM).
     */
    public function getFormattedBookingTimeAttribute(): string
    {
        return substr($this->booking_time ?? '', 0, 5);
    }

    /**
     * Determine if this is an offline / walk-in order.
     */
    public function getIsWalkInAttribute(): bool
    {
        return $this->user_id === null || $this->cashier_id !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the customer who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the cashier who handled this booking.
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Get the staff assigned to this booking.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the booking items (services) for this booking.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope to only today's bookings.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('booking_date', Carbon::today());
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by service category.
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('service_category', $category);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Generate a unique booking code.
     *
     * Format: BK-YYYYMMDD-XXXX (e.g., BK-20240102-0001)
     */
    public static function generateBookingCode(): string
    {
        $date = Carbon::today()->format('Ymd');
        $prefix = 'BK-' . $date . '-';

        $lastBooking = static::where('booking_code', 'like', $prefix . '%')
            ->orderBy('booking_code', 'desc')
            ->first();

        if ($lastBooking) {
            $lastNumber = (int) Str::afterLast($lastBooking->booking_code, '-');
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a queue number for a given category and date.
     *
     * Format: B-001 (barbershop) or M-001 (mua)
     */
    public static function generateQueueNumber(string $category, string|Carbon $date): string
    {
        $prefix = $category === 'barbershop' ? 'B' : 'M';

        $count = static::where('service_category', $category)
            ->whereDate('booking_date', $date)
            ->count();

        $nextNumber = $count + 1;

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the Indonesian status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get the Tailwind CSS color class for the status badge.
     */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
