<?php
// app/Models/HaltePhoto.php - FIXED VERSION

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HaltePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'halte_id',
        'photo_path',
        'description',
        'is_primary',
        'file_size',
        'file_type',
        'original_name'
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
     * Get formatted file size
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
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->photo_path, PATHINFO_EXTENSION);
    }

    /**
     * Check if image is primary
     */
    public function isPrimary()
    {
        return $this->is_primary;
    }

    /**
     * Scopes
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
     * FIXED: Boot method - ONLY DELETE FILE, DON'T CASCADE DELETE HALTE
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

        // FIXED: When deleting photo, ONLY delete file and handle primary photo
        // DO NOT DELETE HALTE
        static::deleting(function ($photo) {
            // Delete file from storage
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }

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
