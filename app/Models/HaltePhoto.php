<?php
// app/Models/HaltePhoto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaltePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'halte_id',
        'photo_path',
        'description',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    /**
     * Relationship with halte
     */
    public function halte()
    {
        return $this->belongsTo(Halte::class);
    }

    /**
     * Get full photo URL
     */
    public function getPhotoUrlAttribute()
    {
        return asset('storage/' . $this->photo_path);
    }
}
