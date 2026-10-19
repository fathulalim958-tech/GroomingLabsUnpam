<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'title',
        'tagline',
        'description',
        'banner_image',
        'facilities',
        'terms',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get facilities as an array.
     */
    public function getFacilitiesListAttribute(): array
    {
        if (empty($this->facilities)) {
            return [];
        }

        // Support either JSON or newline-separated string
        if (str_starts_with(trim($this->facilities), '[')) {
            $decoded = json_decode($this->facilities, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return array_values(array_filter(array_map('trim', explode("\n", $this->facilities))));
    }

    /**
     * Get booking terms as an array.
     */
    public function getTermsListAttribute(): array
    {
        if (empty($this->terms)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $this->terms))));
    }
}
