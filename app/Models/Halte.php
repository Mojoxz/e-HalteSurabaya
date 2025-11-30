<?php
// app/Models/Halte.php - UPDATED WITH DOCUMENTS

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
        return $this->hasMany(HaltePhoto::class)
                   ->orderBy('is_primary', 'desc')
                   ->orderBy('created_at', 'asc');
    }

    /**
     * Relationship with documents
     */
    public function documents()
    {
        return $this->hasMany(HalteDocument::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get SIMBADA documents only
     */
    public function simbadaDocuments()
    {
        return $this->hasMany(HalteDocument::class)
                    ->where('document_type', 'simbada')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get other documents
     */
    public function otherDocuments()
    {
        return $this->hasMany(HalteDocument::class)
                    ->where('document_type', 'other')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get primary photo
     */
    public function primaryPhoto()
    {
        return $this->hasOne(HaltePhoto::class)->where('is_primary', true);
    }

    /**
     * Get secondary photos (non-primary)
     */
    public function secondaryPhotos()
    {
        return $this->hasMany(HaltePhoto::class)
                   ->where('is_primary', false)
                   ->orderBy('created_at', 'asc');
    }

    /**
     * Relationship with rental histories
     */
    public function rentalHistories()
    {
        return $this->hasMany(RentalHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get current/latest rental history
     */
    public function currentRental()
    {
        return $this->hasOne(RentalHistory::class)->latest();
    }

    /**
     * Check if halte is currently rented - FIXED LOGIC
     */
    public function isCurrentlyRented()
    {
        if (!$this->is_rented || !$this->rent_start_date || !$this->rent_end_date) {
            return false;
        }

        $now = Carbon::now();
        return $now->between($this->rent_start_date, $this->rent_end_date);
    }

    /**
     * Check if rental is expired
     */
    public function isRentalExpired()
    {
        if (!$this->is_rented || !$this->rent_end_date) {
            return false;
        }

        return Carbon::now()->isAfter($this->rent_end_date);
    }

    /**
     * Get rental status for map display
     */
    public function getRentalStatusAttribute()
    {
        if ($this->isCurrentlyRented()) {
            return 'rented';
        }

        return 'available';
    }

    /**
     * Get detailed rental status text
     */
    public function getDetailedRentalStatusAttribute()
    {
        if (!$this->is_rented) {
            return 'Tidak Disewa';
        }

        if ($this->isCurrentlyRented()) {
            return 'Sedang Disewa';
        }

        if ($this->isRentalExpired()) {
            return 'Sewa Berakhir';
        }

        return 'Akan Disewa';
    }

    /**
     * Get all photos URLs
     */
    public function getPhotoUrlsAttribute()
    {
        return $this->photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => asset('storage/' . $photo->photo_path),
                'description' => $photo->description,
                'is_primary' => $photo->is_primary
            ];
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
     * Get coordinates as array for easier JSON handling
     */
    public function getCoordinatesAttribute()
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude
        ];
    }

    /**
     * Get formatted address
     */
    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            "Koordinat: {$this->latitude}, {$this->longitude}"
        ]);

        return implode(' - ', $parts);
    }

    /**
     * Check if halte has photos
     */
    public function hasPhotos()
    {
        return $this->photos()->count() > 0;
    }

    /**
     * Get photos count
     */
    public function getPhotosCountAttribute()
    {
        return $this->photos()->count();
    }

    /**
     * Check if halte has documents
     */
    public function hasDocuments()
    {
        return $this->documents()->count() > 0;
    }

    /**
     * Check if halte has SIMBADA documents
     */
    public function hasSimbadaDocuments()
    {
        return $this->simbadaDocuments()->count() > 0;
    }

    /**
     * Scope for available haltes
     */
    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('is_rented', false)
              ->orWhere(function($subQ) {
                  $subQ->where('is_rented', true)
                       ->where('rent_end_date', '<', Carbon::now());
              });
        });
    }

    /**
     * Scope for rented haltes
     */
    public function scopeRented($query)
    {
        return $query->where('is_rented', true)
                    ->where('rent_start_date', '<=', Carbon::now())
                    ->where('rent_end_date', '>=', Carbon::now());
    }

    /**
     * Scope for haltes with SIMBADA
     */
    public function scopeWithSimbada($query)
    {
        return $query->where('simbada_registered', true);
    }

    /**
     * Scope for haltes without SIMBADA
     */
    public function scopeWithoutSimbada($query)
    {
        return $query->where('simbada_registered', false);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting halte, also delete associated files
        static::deleting(function ($halte) {
            foreach ($halte->photos as $photo) {
                if (\Storage::disk('public')->exists($photo->photo_path)) {
                    \Storage::disk('public')->delete($photo->photo_path);
                }
            }

            foreach ($halte->documents as $document) {
                if (\Storage::disk('public')->exists($document->document_path)) {
                    \Storage::disk('public')->delete($document->document_path);
                }
            }
        });
    }
}
