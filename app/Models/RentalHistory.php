<?php
// app/Models/RentalHistory.php

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
     * Relationship with user who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
