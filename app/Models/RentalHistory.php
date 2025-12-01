<?php
// app/Models/RentalHistory.php - UPDATED WITH DOCUMENTS

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'halte_id',
        'rented_by',
        'rent_start_date',
        'rent_end_date',
        'rental_cost',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'rent_start_date' => 'date',
        'rent_end_date' => 'date',
        'rental_cost' => 'decimal:2'
    ];

    /**
     * Relationship with halte
     */
    public function halte()
    {
        return $this->belongsTo(Halte::class);
    }

    /**
     * Relationship with creator (user who created this record)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with documents - NEW
     */
    public function documents()
    {
        return $this->hasMany(RentalDocument::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if rental has documents - NEW
     */
    public function hasDocuments()
    {
        return $this->documents()->count() > 0;
    }

    /**
     * Get formatted rental cost
     */
    public function getFormattedRentalCostAttribute()
    {
        return 'Rp ' . number_format($this->rental_cost, 0, ',', '.');
    }

    /**
     * Get rental duration in days
     */
    public function getDurationInDaysAttribute()
    {
        return $this->rent_start_date->diffInDays($this->rent_end_date);
    }

    /**
     * Check if rental is currently active
     */
    public function isActive()
    {
        $now = now();
        return $now->between($this->rent_start_date, $this->rent_end_date);
    }

    /**
     * Check if rental is upcoming
     */
    public function isUpcoming()
    {
        return now()->isBefore($this->rent_start_date);
    }

    /**
     * Check if rental has expired
     */
    public function hasExpired()
    {
        return now()->isAfter($this->rent_end_date);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        if ($this->isActive()) {
            return 'bg-success';
        } elseif ($this->isUpcoming()) {
            return 'bg-warning text-dark';
        } else {
            return 'bg-secondary';
        }
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        if ($this->isActive()) {
            return 'Aktif';
        } elseif ($this->isUpcoming()) {
            return 'Akan Datang';
        } else {
            return 'Selesai';
        }
    }
}
