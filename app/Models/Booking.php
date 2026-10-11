<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'customer_name',
        'category',
        'service_name',
        'service_price',
        'staff_name',
        'cashier_id',
        'user_id',
        'notes',
        'appointment_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'appointment_at' => 'datetime',
            'service_price' => 'integer',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
