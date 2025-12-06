<?php
// app/Http/Controllers/DocumentController.php - FIXED ACCESS CONTROL

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\HalteDocument;
use App\Models\RentalDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    // HAPUS atau COMMENT middleware di constructor
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    /**
     * View Halte Document in Browser - DIRECT VIEW (NO WRAPPER)
     */
    public function viewHalteDocument($id)
    {
        try {
            // Verifikasi user sudah login
            if (!Auth::check()) {
                abort(403, 'Anda harus login terlebih dahulu');
            }

            $document = HalteDocument::findOrFail($id);
            $filePath = storage_path('app/public/' . $document->document_path);

            if (!file_exists($filePath)) {
                Log::error('File not found: ' . $filePath);
                abort(404, 'File tidak ditemukan di: ' . $document->document_path);
            }

            $mimeType = mime_content_type($filePath);

            // For PDF - Direct view in browser
            if ($document->isPdf()) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . basename($document->document_name) . '"',
                ]);
            }

            // For images - Direct view in browser
            if ($document->isImage()) {
                return response()->file($filePath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . basename($document->document_name) . '"',
                ]);
            }

            // For other files, redirect to download
            return redirect()->route('admin.haltes.documents.download', $id);

        } catch (\Exception $e) {
            Log::error('Error viewing halte document: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            abort(500, 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download Halte Document
     */
    public function downloadHalteDocument($id)
    {
        try {
            if (!Auth::check()) {
                abort(403, 'Anda harus login terlebih dahulu');
            }

            $document = HalteDocument::findOrFail($id);
            $path = storage_path('app/public/' . $document->document_path);

            if (!file_exists($path)) {
                Log::error('File not found for download: ' . $path);
                abort(404, 'File tidak ditemukan');
            }

            return response()->download($path, $document->document_name, [
                'Content-Type' => mime_content_type($path)
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading halte document: ' . $e->getMessage());
            abort(500, 'Gagal mengunduh dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Delete Halte Document
     */
    public function deleteHalteDocument($id)
    {
        try {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $document = HalteDocument::findOrFail($id);

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
            Log::error('Error deleting halte document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View Rental Document in Browser - DIRECT VIEW (NO WRAPPER)
     */
    public function viewRentalDocument($id)
    {
        try {
            if (!Auth::check()) {
                abort(403, 'Anda harus login terlebih dahulu');
            }

            $document = RentalDocument::findOrFail($id);
            $filePath = storage_path('app/public/' . $document->document_path);

            if (!file_exists($filePath)) {
                Log::error('File not found: ' . $filePath);
                abort(404, 'File tidak ditemukan di: ' . $document->document_path);
            }

            $mimeType = mime_content_type($filePath);

            // For PDF - Direct view in browser
            if ($document->isPdf()) {
                return response()->file($filePath, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . basename($document->document_name) . '"',
                ]);
            }

            // For images - Direct view in browser
            if ($document->isImage()) {
                return response()->file($filePath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . basename($document->document_name) . '"',
                ]);
            }

            // For other files, redirect to download
            return redirect()->route('admin.rentals.documents.download', $id);

        } catch (\Exception $e) {
            Log::error('Error viewing rental document: ' . $e->getMessage());
            Log::error('Document ID: ' . $id);
            if (isset($filePath)) {
                Log::error('File path: ' . $filePath);
            }
            abort(500, 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download Rental Document
     */
    public function downloadRentalDocument($id)
    {
        try {
            if (!Auth::check()) {
                abort(403, 'Anda harus login terlebih dahulu');
            }

            $document = RentalDocument::findOrFail($id);
            $path = storage_path('app/public/' . $document->document_path);

            if (!file_exists($path)) {
                Log::error('File not found for download: ' . $path);
                abort(404, 'File tidak ditemukan');
            }

            return response()->download($path, $document->document_name, [
                'Content-Type' => mime_content_type($path)
            ]);

        } catch (\Exception $e) {
            Log::error('Error downloading rental document: ' . $e->getMessage());
            abort(500, 'Gagal mengunduh dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Delete Rental Document
     */
    public function deleteRentalDocument($id)
    {
        try {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

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
     * Upload Halte Documents (AJAX)
     */
    public function uploadHalteDocuments(Request $request, $halteId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'document_type' => 'required|in:simbada,other'
        ]);

        try {
            $uploadedDocuments = [];

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileSize = $file->getSize();

                    // Store file
                    $path = $file->store('halte-documents', 'public');

                    // Create database record
                    $document = HalteDocument::create([
                        'halte_id' => $halteId,
                        'document_type' => $request->document_type,
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
            Log::error('Error uploading halte documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload Rental Documents (AJAX)
     */
    public function uploadRentalDocuments(Request $request, $rentalHistoryId)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        try {
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
}
