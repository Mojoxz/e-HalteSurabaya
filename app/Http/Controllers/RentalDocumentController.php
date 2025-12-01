<?php
// app/Http/Controllers/RentalDocumentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalDocument;
use App\Models\RentalHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RentalDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * View Rental Document in Browser
     */
    public function view($id)
    {
        try {
            $document = RentalDocument::findOrFail($id);
            $path = storage_path('app/public/' . $document->document_path);

            if (!file_exists($path)) {
                abort(404, 'File tidak ditemukan');
            }

            // Get mime type
            $mimeType = mime_content_type($path);

            // For PDF, display inline
            if ($document->isPdf()) {
                return response()->file($path, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $document->document_name . '"'
                ]);
            }

            // For images, display inline
            if ($document->isImage()) {
                return response()->file($path, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $document->document_name . '"'
                ]);
            }

            // For other files, show in viewer page
            return view('admin.documents.rental-viewer', [
                'document' => $document
            ]);

        } catch (\Exception $e) {
            Log::error('Error viewing rental document: ' . $e->getMessage());
            abort(404, 'Dokumen tidak ditemukan');
        }
    }

    /**
     * Download Rental Document
     */
    public function download($id)
    {
        try {
            $document = RentalDocument::findOrFail($id);
            $path = storage_path('app/public/' . $document->document_path);

            if (!file_exists($path)) {
                abort(404, 'File tidak ditemukan');
            }

            return response()->download($path, $document->document_name);

        } catch (\Exception $e) {
            Log::error('Error downloading rental document: ' . $e->getMessage());
            abort(404, 'Dokumen tidak ditemukan');
        }
    }

    /**
     * Delete Rental Document
     */
    public function delete($id)
    {
        try {
            $document = RentalDocument::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($document->document_path)) {
                Storage::disk('public')->delete($document->document_path);
            }

            // Delete database record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting rental document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload Rental Documents (AJAX)
     */
    public function upload(Request $request, $rentalHistoryId)
    {
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        try {
            // Verify rental history exists
            $rentalHistory = RentalHistory::findOrFail($rentalHistoryId);

            $uploadedDocuments = [];

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileSize = $file->getSize();

                    // Store file
                    $path = $file->store('rental-documents', 'public');

                    // Create database record
                    $document = RentalDocument::create([
                        'rental_history_id' => $rentalHistoryId,
                        'document_name' => $originalName,
                        'document_path' => $path,
                        'file_type' => $extension,
                        'file_size' => $fileSize,
                        'description' => $request->document_descriptions[$index] ?? null,
                        'uploaded_by' => Auth::id()
                    ]);

                    $uploadedDocuments[] = $document;
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedDocuments) . ' dokumen berhasil diupload',
                'documents' => $uploadedDocuments
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading rental documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update document description
     */
    public function updateDescription(Request $request, $id)
    {
        try {
            $document = RentalDocument::findOrFail($id);

            $request->validate([
                'description' => 'nullable|string|max:500'
            ]);

            $document->update([
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Deskripsi dokumen berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating document description: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate deskripsi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all documents for a rental history (AJAX)
     */
    public function getDocuments($rentalHistoryId)
    {
        try {
            $documents = RentalDocument::where('rental_history_id', $rentalHistoryId)
                                      ->orderBy('created_at', 'desc')
                                      ->get();

            return response()->json([
                'success' => true,
                'documents' => $documents
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting rental documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dokumen'
            ], 500);
        }
    }

    /**
     * Bulk delete documents for a rental
     */
    public function bulkDelete(Request $request, $rentalHistoryId)
    {
        try {
            $request->validate([
                'document_ids' => 'required|array',
                'document_ids.*' => 'exists:rental_documents,id'
            ]);

            $deletedCount = 0;

            foreach ($request->document_ids as $documentId) {
                $document = RentalDocument::where('id', $documentId)
                                         ->where('rental_history_id', $rentalHistoryId)
                                         ->first();

                if ($document) {
                    // Delete file from storage
                    if (Storage::disk('public')->exists($document->document_path)) {
                        Storage::disk('public')->delete($document->document_path);
                    }

                    // Delete database record
                    $document->delete();
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' dokumen berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error bulk deleting rental documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
