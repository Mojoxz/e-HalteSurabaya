<?php
// app/Http/Controllers/AdminController.php - FIXED VERSION WITH AUTO SORTING AND DOCUMENT UPLOAD

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;
use App\Models\HaltePhoto;
use App\Models\HalteDocument;
use App\Models\RentalHistory;
use App\Models\RentalDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $totalHaltes = Halte::count();
        $availableHaltes = Halte::available()->count();
        $rentedHaltes = Halte::rented()->count();
        $totalRevenue = RentalHistory::sum('rental_cost');

        // Recent rental activities
        $recentRentals = RentalHistory::with(['halte', 'creator'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        return view('admin.dashboard', compact(
            'totalHaltes',
            'availableHaltes',
            'rentedHaltes',
            'totalRevenue',
            'recentRentals'
        ));
    }

    /**
     * List all haltes - FIXED WITH AUTO SORTING AND PERSISTENT STATE
     */
    public function halteList(Request $request)
    {
        // FIXED: Store current filter/sort state in session (except for reset)
        if (!$request->has('reset')) {
            $currentParams = $request->only(['search', 'status', 'simbada', 'sort', 'direction']);
            if (!empty($currentParams)) {
                $request->session()->put('halte_sort', $currentParams);
            }
        } else {
            // Clear session when reset is triggered
            $request->session()->forget('halte_sort');
        }

        $query = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }, 'documents', 'rentalHistories']);

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where(function($q) {
                    $q->where('is_rented', false)
                      ->orWhere('rent_end_date', '<', now());
                });
            } elseif ($request->status === 'rented') {
                $query->where('is_rented', true)
                      ->where('rent_end_date', '>=', now());
            }
        }

        // Apply SIMBADA filter
        if ($request->filled('simbada')) {
            $query->where('simbada_registered', $request->simbada);
        }

        // FIXED: Auto sorting with persistent state
        $sortField = $request->get('sort', 'name'); // Default sort by name
        $sortDirection = $request->get('direction', 'asc'); // Default ascending

        // Validate sort fields to prevent SQL injection
        $allowedSortFields = ['name', 'created_at', 'updated_at', 'status'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }

        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'asc';

        // Apply sorting
        $query->orderBy($sortField, $sortDirection);

        $haltes = $query->paginate(10);

        // Preserve ALL query parameters in pagination links
        $haltes->appends($request->query());

        return view('admin.haltes.index', compact('haltes', 'sortField', 'sortDirection'));
    }

    /**
     * Show create halte form
     */
    public function halteCreate()
    {
        return view('admin.haltes.create');
    }

/**
 * Update halte - FIXED RENTAL DOCUMENT UPLOAD
 */
public function halteUpdate(Request $request, $id)
{
    try {
        $halte = Halte::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'simbada_number' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'simbada_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'simbada_document_descriptions.*' => 'nullable|string',
            'rent_start_date' => 'nullable|date',
            'rent_end_date' => 'nullable|date|after:rent_start_date',
            'rented_by' => 'nullable|string|max:255',
            'rental_cost' => 'nullable|numeric|min:0',
            'rental_notes' => 'nullable|string',
            'rental_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'rental_document_descriptions.*' => 'nullable|string'
        ]);

        // FIXED: Proper rental status logic
        $isRented = $request->has('is_rented') && $request->is_rented;
        $hasValidRentalDates = $request->filled('rent_start_date') && $request->filled('rent_end_date');

        $updateData = [
            'name' => $request->name,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'simbada_registered' => $request->has('simbada_registered'),
            'simbada_number' => $request->simbada_number,
        ];

        // Handle rental information with proper validation
        if ($isRented && $hasValidRentalDates) {
            $startDate = Carbon::parse($request->rent_start_date);
            $endDate = Carbon::parse($request->rent_end_date);
            $now = Carbon::now();

            $updateData['is_rented'] = true;
            $updateData['rent_start_date'] = $startDate;
            $updateData['rent_end_date'] = $endDate;
            $updateData['rented_by'] = $request->rented_by;

            // Set proper status based on dates
            if ($now->between($startDate, $endDate)) {
                $updateData['status'] = 'rented';
            } elseif ($now->isBefore($startDate)) {
                $updateData['status'] = 'available'; // Future rental
            } else {
                $updateData['status'] = 'available'; // Expired rental
                $updateData['is_rented'] = false;
            }

            // FIXED: Always handle rental documents if uploaded
            // Check if we should create new rental history
            $currentRental = $halte->rentalHistories()->latest()->first();
            $shouldCreateNewHistory = !$currentRental ||
                                      $currentRental->rent_start_date->format('Y-m-d') != $startDate->format('Y-m-d') ||
                                      $currentRental->rent_end_date->format('Y-m-d') != $endDate->format('Y-m-d') ||
                                      $currentRental->rented_by != $request->rented_by;

            if ($shouldCreateNewHistory) {
                // Create new rental history
                $rentalHistory = RentalHistory::create([
                    'halte_id' => $halte->id,
                    'rented_by' => $request->rented_by,
                    'rent_start_date' => $startDate,
                    'rent_end_date' => $endDate,
                    'rental_cost' => $request->rental_cost ?? 0,
                    'notes' => $request->rental_notes,
                    'created_by' => Auth::id()
                ]);

                // Upload documents to NEW rental history
                if ($request->hasFile('rental_documents')) {
                    foreach ($request->file('rental_documents') as $index => $document) {
                        try {
                            $originalName = $document->getClientOriginalName();
                            $extension = $document->getClientOriginalExtension();
                            $path = $document->store('rental-documents', 'public');

                            RentalDocument::create([
                                'rental_history_id' => $rentalHistory->id,
                                'document_name' => $originalName,
                                'document_path' => $path,
                                'file_type' => $extension,
                                'file_size' => $document->getSize(),
                                'description' => $request->rental_document_descriptions[$index] ?? null,
                                'uploaded_by' => Auth::id()
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error uploading rental document: ' . $e->getMessage());
                        }
                    }
                }
            } else {
                // FIXED: Upload documents to EXISTING rental history (this was missing!)
                if ($request->hasFile('rental_documents') && $currentRental) {
                    foreach ($request->file('rental_documents') as $index => $document) {
                        try {
                            $originalName = $document->getClientOriginalName();
                            $extension = $document->getClientOriginalExtension();
                            $path = $document->store('rental-documents', 'public');

                            RentalDocument::create([
                                'rental_history_id' => $currentRental->id, // Use existing rental history
                                'document_name' => $originalName,
                                'document_path' => $path,
                                'file_type' => $extension,
                                'file_size' => $document->getSize(),
                                'description' => $request->rental_document_descriptions[$index] ?? null,
                                'uploaded_by' => Auth::id()
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error uploading rental document: ' . $e->getMessage());
                        }
                    }
                }
            }
        } else {
            $updateData['is_rented'] = false;
            $updateData['rent_start_date'] = null;
            $updateData['rent_end_date'] = null;
            $updateData['rented_by'] = null;
            $updateData['status'] = 'available';
        }

        // Update halte data
        $halte->update($updateData);

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                try {
                    $path = $photo->store('halte-photos', 'public');

                    HaltePhoto::create([
                        'halte_id' => $halte->id,
                        'photo_path' => $path,
                        'description' => $request->photo_descriptions[$index] ?? null,
                        'is_primary' => false
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error uploading photo: ' . $e->getMessage());
                }
            }
        }

        // Handle SIMBADA document uploads
        if ($request->hasFile('simbada_documents')) {
            foreach ($request->file('simbada_documents') as $index => $document) {
                try {
                    $originalName = $document->getClientOriginalName();
                    $extension = $document->getClientOriginalExtension();
                    $path = $document->store('halte-documents/simbada', 'public');

                    HalteDocument::create([
                        'halte_id' => $halte->id,
                        'document_type' => 'simbada',
                        'document_name' => $originalName,
                        'document_path' => $path,
                        'file_type' => $extension,
                        'file_size' => $document->getSize(),
                        'description' => $request->simbada_document_descriptions[$index] ?? null,
                        'uploaded_by' => Auth::id()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error uploading SIMBADA document: ' . $e->getMessage());
                }
            }
        }

        // Redirect with preserved sort parameters
        $redirectParams = [];
        if ($request->session()->has('halte_sort')) {
            $redirectParams = $request->session()->get('halte_sort');
        }

        return redirect()->route('admin.haltes.index', $redirectParams)
            ->with('success', 'Halte berhasil diupdate');

    } catch (\Exception $e) {
        Log::error('Error updating halte: ' . $e->getMessage());
        return back()->withInput()
            ->with('error', 'Gagal mengupdate halte: ' . $e->getMessage());
    }
}


    /**
     * Delete halte - FIXED WITH PERSISTENT SORT STATE
     */
    public function halteDestroy($id)
    {
        try {
            $halte = Halte::findOrFail($id);

            // Delete photos from storage
            foreach ($halte->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo_path)) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
            }

            // Delete documents from storage - NEW
            foreach ($halte->documents as $document) {
                if (Storage::disk('public')->exists($document->document_path)) {
                    Storage::disk('public')->delete($document->document_path);
                }
            }

            $halte->delete();

            // FIXED: Redirect with preserved sort parameters
            $redirectParams = [];
            if (request()->session()->has('halte_sort')) {
                $redirectParams = request()->session()->get('halte_sort');
            }

            return redirect()->route('admin.haltes.index', $redirectParams)->with('success', 'Halte berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting halte: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus halte');
        }
    }

    /**
     * Delete photo - COMPLETELY FIXED
     */
    public function deletePhoto($id)
    {
        try {
            $photo = HaltePhoto::findOrFail($id);
            $halteId = $photo->halte_id;
            $wasPrimary = $photo->is_primary;

            // Delete file from storage
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }

            // Delete only the photo record, NOT the halte
            $photo->delete();

            // If deleted photo was primary, make first available photo primary
            if ($wasPrimary) {
                $firstPhoto = HaltePhoto::where('halte_id', $halteId)->first();
                if ($firstPhoto) {
                    $firstPhoto->update(['is_primary' => true]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set primary photo - FIXED WITH BETTER ERROR HANDLING
     */
    public function setPrimaryPhoto($id)
    {
        try {
            $photo = HaltePhoto::findOrFail($id);

            // Remove primary status from other photos of the same halte
            HaltePhoto::where('halte_id', $photo->halte_id)
                     ->where('id', '!=', $photo->id)
                     ->update(['is_primary' => false]);

            // Set this photo as primary
            $photo->update(['is_primary' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Foto utama berhasil diatur'
            ]);

        } catch (\Exception $e) {
            Log::error('Error setting primary photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengatur foto utama: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete document - NEW METHOD
     */
    public function deleteDocument($id)
    {
        try {
            $document = HalteDocument::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($document->document_path)) {
                Storage::disk('public')->delete($document->document_path);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete rental document - NEW METHOD
     */
    public function deleteRentalDocument($id)
    {
        try {
            $document = RentalDocument::findOrFail($id);

            // Delete file from storage
            if (Storage::disk('public')->exists($document->document_path)) {
                Storage::disk('public')->delete($document->document_path);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen sewa berhasil dihapus'
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
 * View SIMBADA document - FIXED FOR INLINE VIEWING
 */
public function viewDocument($id)
{
    try {
        $document = HalteDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $path = Storage::disk('public')->path($document->document_path);
        $mimeType = Storage::disk('public')->mimeType($document->document_path);

        // FIXED: Return response untuk viewing inline
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->document_name . '"'
        ]);
    } catch (\Exception $e) {
        Log::error('Error viewing document: ' . $e->getMessage());
        abort(404, 'Dokumen tidak dapat ditampilkan');
    }
}

/**
 * Download SIMBADA document
 */
public function downloadDocument($id)
{
    try {
        $document = HalteDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $document->document_path,
            $document->document_name
        );
    } catch (\Exception $e) {
        Log::error('Error downloading document: ' . $e->getMessage());
        abort(404, 'Dokumen tidak dapat diunduh');
    }
}

/**
 * View rental document - FIXED FOR INLINE VIEWING
 */
public function viewRentalDocument($id)
{
    try {
        $document = RentalDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $path = Storage::disk('public')->path($document->document_path);
        $mimeType = Storage::disk('public')->mimeType($document->document_path);

        // FIXED: Return response untuk viewing inline
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->document_name . '"'
        ]);
    } catch (\Exception $e) {
        Log::error('Error viewing rental document: ' . $e->getMessage());
        abort(404, 'Dokumen tidak dapat ditampilkan');
    }
}

/**
 * Download rental document
 */
public function downloadRentalDocument($id)
{
    try {
        $document = RentalDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $document->document_path,
            $document->document_name
        );
    } catch (\Exception $e) {
        Log::error('Error downloading rental document: ' . $e->getMessage());
        abort(404, 'Dokumen tidak dapat diunduh');
    }
}
    /**
     * Rental history list - IMPROVED WITH FILTERS
     */
    public function rentalHistory(Request $request)
    {
        $query = RentalHistory::with(['halte', 'creator', 'documents'])
                             ->orderBy('created_at', 'desc');

        // Apply date filter
        if ($request->filled('start_date')) {
            $query->where('rent_start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('rent_end_date', '<=', $request->end_date);
        }

        // Apply halte filter
        if ($request->filled('halte_id')) {
            $query->where('halte_id', $request->halte_id);
        }

        // Apply renter filter
        if ($request->filled('rented_by')) {
            $query->where('rented_by', 'LIKE', '%' . $request->rented_by . '%');
        }

        $histories = $query->paginate(15);

        // Get haltes for filter dropdown
        $haltes = Halte::orderBy('name')->get();

        // Preserve query parameters in pagination links
        $histories->appends($request->query());

        return view('admin.rentals.index', compact('histories', 'haltes'));
    }

    /**
     * Reports dashboard - NEW
     */
    public function reports(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Monthly rental statistics
        $monthlyStats = RentalHistory::selectRaw('
                MONTH(rent_start_date) as month,
                COUNT(*) as total_rentals,
                SUM(rental_cost) as total_revenue,
                COUNT(DISTINCT halte_id) as unique_haltes
            ')
            ->whereYear('rent_start_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top rented haltes
        $topHaltes = DB::table('rental_histories')
            ->join('haltes', 'rental_histories.halte_id', '=', 'haltes.id')
            ->select('haltes.name',
                    DB::raw('COUNT(*) as rental_count'),
                    DB::raw('SUM(rental_histories.rental_cost) as total_revenue'))
            ->whereYear('rental_histories.rent_start_date', $year)
            ->groupBy('haltes.id', 'haltes.name')
            ->orderBy('rental_count', 'desc')
            ->take(10)
            ->get();

        // Revenue by month for chart
        $revenueChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyStats->firstWhere('month', $i);
            $revenueChart[] = [
                'month' => Carbon::create()->month($i)->format('M'),
                'revenue' => $monthData ? $monthData->total_revenue : 0,
                'rentals' => $monthData ? $monthData->total_rentals : 0
            ];
        }

        // Current month detailed stats
        $currentMonthStats = [
            'total_rentals' => RentalHistory::whereYear('rent_start_date', $year)
                                          ->whereMonth('rent_start_date', $month)
                                          ->count(),
            'total_revenue' => RentalHistory::whereYear('rent_start_date', $year)
                                          ->whereMonth('rent_start_date', $month)
                                          ->sum('rental_cost'),
            'active_rentals' => RentalHistory::where('rent_start_date', '<=', now())
                                           ->where('rent_end_date', '>=', now())
                                           ->count(),
            'expired_rentals' => RentalHistory::where('rent_end_date', '<', now())
                                            ->whereYear('rent_end_date', $year)
                                            ->whereMonth('rent_end_date', $month)
                                            ->count()
        ];

        return view('admin.reports.index', compact(
            'monthlyStats',
            'topHaltes',
            'revenueChart',
            'currentMonthStats',
            'year',
            'month'
        ));
    }

    /**
     * Generate PDF Report - NEW
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:monthly,yearly,custom',
            'start_date' => 'required_if:report_type,custom|date',
            'end_date' => 'required_if:report_type,custom|date|after:start_date',
            'year' => 'required_if:report_type,yearly|numeric',
            'month' => 'required_if:report_type,monthly|numeric|between:1,12'
        ]);

        $reportType = $request->report_type;
        $query = RentalHistory::with(['halte', 'creator']);

        // Set date filters based on report type
        if ($reportType === 'monthly') {
            $year = $request->year ?? date('Y');
            $month = $request->month ?? date('m');
            $query->whereYear('rent_start_date', $year)
                  ->whereMonth('rent_start_date', $month);
            $reportTitle = "Laporan Bulanan - " . Carbon::create($year, $month)->format('F Y');
        } elseif ($reportType === 'yearly') {
            $year = $request->year ?? date('Y');
            $query->whereYear('rent_start_date', $year);
            $reportTitle = "Laporan Tahunan - " . $year;
        } else { // custom
            $query->whereBetween('rent_start_date', [$request->start_date, $request->end_date]);
            $reportTitle = "Laporan Custom - " . $request->start_date . " sampai " . $request->end_date;
        }

        $rentals = $query->orderBy('rent_start_date', 'desc')->get();

        $summary = [
            'total_rentals' => $rentals->count(),
            'total_revenue' => $rentals->sum('rental_cost'),
            'unique_haltes' => $rentals->pluck('halte_id')->unique()->count(),
            'average_rental_cost' => $rentals->count() > 0 ? $rentals->avg('rental_cost') : 0
        ];

        // Return view for now (can be converted to PDF later)
        return view('admin.reports.pdf', compact('rentals', 'summary', 'reportTitle'));
    }
}
