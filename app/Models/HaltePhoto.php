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
        'is_primary',
        // BARU: tambahan field untuk better file management
        'file_size',        // ukuran file dalam bytes
        'file_type',        // mime type (image/jpeg, image/png, etc.)
        'original_name'     // nama asli file
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'file_size' => 'integer'
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

    /**
     * BARU: Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < 3; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * BARU: Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->photo_path, PATHINFO_EXTENSION);
    }

    /**
     * BARU: Check if image is primary
     */
    public function isPrimary()
    {
        return $this->is_primary;
    }

    /**
     * BARU: Scopes
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeSecondary($query)
    {
        return $query->where('is_primary', false);
    }

    public function scopeByHalte($query, $halteId)
    {
        return $query->where('halte_id', $halteId);
    }

    /**
     * BARU: Boot method untuk handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // When setting a photo as primary, unset others for the same halte
        static::saved(function ($photo) {
            if ($photo->is_primary) {
                // Remove primary status from other photos of the same halte
                static::where('halte_id', $photo->halte_id)
                     ->where('id', '!=', $photo->id)
                     ->update(['is_primary' => false]);
            }
        });

        // When deleting photo, delete file from storage and handle primary photo
        static::deleting(function ($photo) {
            // Delete file from storage
            \Storage::disk('public')->delete($photo->photo_path);

            // If this was the primary photo, set another photo as primary
            if ($photo->is_primary) {
                $nextPhoto = static::where('halte_id', $photo->halte_id)
                    ->where('id', '!=', $photo->id)
                    ->oldest()
                    ->first();

                if ($nextPhoto) {
                    $nextPhoto->update(['is_primary' => true]);
                }
            }
        });

        // When creating new photo, if it's the first photo, make it primary
        static::creating(function ($photo) {
            $existingPhotos = static::where('halte_id', $photo->halte_id)->count();

            if ($existingPhotos === 0) {
                $photo->is_primary = true;
            }
        });
    }
}
