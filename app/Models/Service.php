<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'duration_minutes', 'price', 'is_active'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price' => 'integer', 'duration_minutes' => 'integer'];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
