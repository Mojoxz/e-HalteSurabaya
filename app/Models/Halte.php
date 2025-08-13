<?php
// app/Models/Halte.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Halte extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'address',
        'is_rented',
        'rent_start_date',
        'rent_end_date',
        'rented_by',
        'simbada_registered',
        'simbada_number',
        'status'
    ];

    protected $casts = [
        'is_rented' => 'boolean',
        'simbada_registered' => 'boolean',
        'rent_start_date' => 'date',
        'rent_end_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Relationship with photos
     */
    public function photos()
    {
        return $this->hasMany(HaltePhoto::class);
    }

    /**
     * Get primary photo
     */
    public function primaryPhoto()
    {
        return $this->hasOne(HaltePhoto::class)->where('is_primary', true);
    }

    /**
     * Relationship with rental histories
     */
    public function rentalHistories()
    {
        return $this->hasMany(RentalHistory::class);
    }

    /**
     * Check if halte is currently rented
     */
    public function isCurrentlyRented()
    {
        if (!$this->is_rented || !$this->rent_end_date) {
            return false;
        }

        return Carbon::now()->isBefore($this->rent_end_date);
    }

    /**
     * Get rental status for map display
     */
    public function getRentalStatusAttribute()
    {
        if ($this->isCurrentlyRented()) {
            return 'rented'; // Red marker
        }

        return 'available'; // Green marker
    }

    /**
     * Get all photos URLs
     */
    public function getPhotoUrlsAttribute()
    {
        return $this->photos->map(function ($photo) {
            return asset('storage/' . $photo->photo_path);
        });
    }

    /**
     * Get primary photo URL
     */
    public function getPrimaryPhotoUrlAttribute()
    {
        $primaryPhoto = $this->primaryPhoto;
        if ($primaryPhoto) {
            return asset('storage/' . $primaryPhoto->photo_path);
        }

        $firstPhoto = $this->photos->first();
        if ($firstPhoto) {
            return asset('storage/' . $firstPhoto->photo_path);
        }

        return asset('images/halte-default.png');
    }

    /**
     * Scope for available haltes
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                    ->where(function($q) {
                        $q->where('is_rented', false)
                          ->orWhere('rent_end_date', '<', Carbon::now());
                    });
    }

    /**
     * Scope for rented haltes
     */
    public function scopeRented($query)
    {
        return $query->where('is_rented', true)
                    ->where('rent_end_date', '>=', Carbon::now());
    }
}
