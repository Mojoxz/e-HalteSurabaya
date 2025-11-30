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
     * Relationship with user who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with rental documents
     */
    public function documents()
    {
        return $this->hasMany(RentalDocument::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if rental has documents
     */
    public function hasDocuments()
    {
        return $this->documents()->count() > 0;
    }

    /**
     * Get documents count
     */
    public function getDocumentsCountAttribute()
    {
        return $this->documents()->count();
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // When deleting rental history, also delete associated documents
        static::deleting(function ($rental) {
            foreach ($rental->documents as $document) {
                if (\Storage::disk('public')->exists($document->document_path)) {
                    \Storage::disk('public')->delete($document->document_path);
                }
            }
        });
    }
}
