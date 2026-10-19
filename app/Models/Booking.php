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
        'cashier_id',
        'staff_id',
        'service_category',
        'booking_date',
        'booking_time',
        'queue_number',
        'notes',
        'total_price',
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
        ];
    }

    /**
     * Status labels in Indonesian.
     *
     * @var array<string, string>
     */
    public const STATUS_LABELS = [
        'waiting' => 'Menunggu',
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
        'waiting' => 'bg-yellow-100 text-yellow-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'skipped' => 'bg-orange-100 text-orange-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];

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
