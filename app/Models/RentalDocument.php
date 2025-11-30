<?php
// app/Models/RentalDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RentalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_history_id',
        'document_name',
        'document_path',
        'file_type',
        'file_size',
        'description',
        'uploaded_by'
    ];

    /**
     * Relationship with rental history
     */
    public function rentalHistory()
    {
        return $this->belongsTo(RentalHistory::class);
    }

    /**
     * Relationship with uploader
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get document URL
     */
    public function getDocumentUrlAttribute()
    {
        return asset('storage/' . $this->document_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Check if document is PDF
     */
    public function isPdf()
    {
        return strtolower($this->file_type) === 'pdf';
    }

    /**
     * Check if document is image
     */
    public function isImage()
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Get icon class based on file type
     */
    public function getIconClassAttribute()
    {
        if ($this->isPdf()) {
            return 'fas fa-file-pdf text-danger';
        } elseif ($this->isImage()) {
            return 'fas fa-file-image text-primary';
        } elseif (in_array(strtolower($this->file_type), ['doc', 'docx'])) {
            return 'fas fa-file-word text-info';
        } elseif (in_array(strtolower($this->file_type), ['xls', 'xlsx'])) {
            return 'fas fa-file-excel text-success';
        } else {
            return 'fas fa-file text-secondary';
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Delete file from storage when document is deleted
        static::deleting(function ($document) {
            if (Storage::disk('public')->exists($document->document_path)) {
                Storage::disk('public')->delete($document->document_path);
            }
        });
    }
}
