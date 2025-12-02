<?php
// app/Http/Controllers/AdminController.php - CLEAN VERSION WITHOUT DOCUMENT HANDLING

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
 * List all haltes
 */
public function halteList(Request $request)
{
    if (!$request->has('reset')) {
        $currentParams = $request->only(['search', 'status', 'simbada', 'sort', 'direction']);
        if (!empty($currentParams)) {
            $request->session()->put('halte_sort', $currentParams);
        }
    } else {
        $request->session()->forget('halte_sort');
    }

    $query = Halte::with(['photos' => function($query) {
        $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
    }, 'rentalHistories']);

    if ($request->filled('search')) {
        $query->where('name', 'LIKE', '%' . $request->search . '%');
    }

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

    if ($request->filled('simbada')) {
        $query->where('simbada_registered', $request->simbada);
    }

    // PERUBAHAN UTAMA: Default sort adalah 'id' bukan 'name'
    $sortField = $request->get('sort', 'id');
    $sortDirection = $request->get('direction', 'asc');

    // Tambahkan 'id' ke allowed sort fields
    $allowedSortFields = ['id', 'name', 'created_at', 'updated_at', 'status'];
    if (!in_array($sortField, $allowedSortFields)) {
        $sortField = 'id';
    }

    $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'asc';

    $query->orderBy($sortField, $sortDirection);

    $haltes = $query->paginate(10);
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
     * Store new halte
     */
    public function halteStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string',
            'simbada_number' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'simbada_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'rent_start_date' => 'nullable|date',
            'rent_end_date' => 'nullable|date|after:rent_start_date',
            'rented_by' => 'nullable|string|max:255',
            'rental_cost' => 'nullable|numeric|min:0',
            'rental_notes' => 'nullable|string',
            'rental_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $isRented = $request->has('is_rented') && $request->is_rented;
        $hasValidRentalDates = $request->filled('rent_start_date') && $request->filled('rent_end_date');

        $halteData = [
            'name' => $request->name,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
            'simbada_registered' => $request->has('simbada_registered'),
            'simbada_number' => $request->simbada_number,
        ];

        // Handle rental information
        if ($isRented && $hasValidRentalDates) {
            $startDate = Carbon::parse($request->rent_start_date);
            $endDate = Carbon::parse($request->rent_end_date);
            $now = Carbon::now();

            $halteData['is_rented'] = true;
            $halteData['rent_start_date'] = $startDate;
            $halteData['rent_end_date'] = $endDate;
            $halteData['rented_by'] = $request->rented_by;

            if ($now->between($startDate, $endDate)) {
                $halteData['status'] = 'rented';
            } elseif ($now->isBefore($startDate)) {
                $halteData['status'] = 'available';
            } else {
                $halteData['status'] = 'available';
                $halteData['is_rented'] = false;
            }
        } else {
            $halteData['is_rented'] = false;
            $halteData['rent_start_date'] = null;
            $halteData['rent_end_date'] = null;
            $halteData['rented_by'] = null;
            $halteData['status'] = 'available';
        }

        $halte = Halte::create($halteData);

        // Create rental history if halte is rented
        $rentalHistory = null;
        if ($isRented && $hasValidRentalDates) {
            $rentalHistory = RentalHistory::create([
                'halte_id' => $halte->id,
                'rented_by' => $request->rented_by,
                'rent_start_date' => $request->rent_start_date,
                'rent_end_date' => $request->rent_end_date,
                'rental_cost' => $request->rental_cost ?? 0,
                'notes' => $request->rental_notes,
                'created_by' => Auth::id()
            ]);
        }

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                try {
                    $path = $photo->store('halte-photos', 'public');

                    HaltePhoto::create([
                        'halte_id' => $halte->id,
                        'photo_path' => $path,
                        'description' => $request->photo_descriptions[$index] ?? null,
                        'is_primary' => $index === 0
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error uploading photo: ' . $e->getMessage());
                }
            }
        }

        // Handle SIMBADA document uploads
        if ($request->hasFile('simbada_documents')) {
            foreach ($request->file('simbada_documents') as $index => $file) {
                try {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileSize = $file->getSize();
                    $path = $file->store('halte-documents', 'public');

                    HalteDocument::create([
                        'halte_id' => $halte->id,
                        'document_type' => 'simbada',
                        'document_name' => $originalName,
                        'document_path' => $path,
                        'file_type' => $extension,
                        'file_size' => $fileSize,
                        'description' => $request->simbada_document_descriptions[$index] ?? null,
                        'uploaded_by' => Auth::id()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error uploading SIMBADA document: ' . $e->getMessage());
                }
            }
        }

        // Handle rental document uploads
        if ($rentalHistory && $request->hasFile('rental_documents')) {
            foreach ($request->file('rental_documents') as $index => $file) {
                try {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $fileSize = $file->getSize();
                    $path = $file->store('rental-documents', 'public');

                    RentalDocument::create([
                        'rental_history_id' => $rentalHistory->id,
                        'document_name' => $originalName,
                        'document_path' => $path,
                        'file_type' => $extension,
                        'file_size' => $fileSize,
                        'description' => $request->rental_document_descriptions[$index] ?? null,
                        'uploaded_by' => Auth::id()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error uploading rental document: ' . $e->getMessage());
                }
            }
        }

        $redirectParams = [];
        if ($request->session()->has('halte_sort')) {
            $redirectParams = $request->session()->get('halte_sort');
        }

        return redirect()->route('admin.haltes.index', $redirectParams)->with('success', 'Halte berhasil ditambahkan');
    }

    /**
     * Show halte details
     */
    public function halteShow($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc');
        }, 'rentalHistories' => function($query) {
            $query->with('documents')->orderBy('created_at', 'desc');
        }, 'simbadaDocuments', 'otherDocuments'])->findOrFail($id);

        return view('admin.haltes.show', compact('halte'));
    }

    /**
     * Show edit halte form
     */
    public function halteEdit($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc');
        }, 'simbadaDocuments', 'rentalHistories' => function($query) {
            $query->with('documents')->latest();
        }])->findOrFail($id);

        return view('admin.haltes.edit', compact('halte'));
    }

    /**
     * Update halte
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
                'rent_start_date' => 'nullable|date',
                'rent_end_date' => 'nullable|date|after:rent_start_date',
                'rented_by' => 'nullable|string|max:255',
                'rental_cost' => 'nullable|numeric|min:0',
                'rental_notes' => 'nullable|string',
                'rental_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);

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

            // Handle rental information
            $rentalHistory = null;
            if ($isRented && $hasValidRentalDates) {
                $startDate = Carbon::parse($request->rent_start_date);
                $endDate = Carbon::parse($request->rent_end_date);
                $now = Carbon::now();

                $updateData['is_rented'] = true;
                $updateData['rent_start_date'] = $startDate;
                $updateData['rent_end_date'] = $endDate;
                $updateData['rented_by'] = $request->rented_by;

                if ($now->between($startDate, $endDate)) {
                    $updateData['status'] = 'rented';
                } elseif ($now->isBefore($startDate)) {
                    $updateData['status'] = 'available';
                } else {
                    $updateData['status'] = 'available';
                    $updateData['is_rented'] = false;
                }

                $currentRental = $halte->rentalHistories()->latest()->first();
                $shouldCreateNewHistory = !$currentRental ||
                                          $currentRental->rent_start_date->format('Y-m-d') != $startDate->format('Y-m-d') ||
                                          $currentRental->rent_end_date->format('Y-m-d') != $endDate->format('Y-m-d') ||
                                          $currentRental->rented_by != $request->rented_by;

                if ($shouldCreateNewHistory) {
                    $rentalHistory = RentalHistory::create([
                        'halte_id' => $halte->id,
                        'rented_by' => $request->rented_by,
                        'rent_start_date' => $startDate,
                        'rent_end_date' => $endDate,
                        'rental_cost' => $request->rental_cost ?? 0,
                        'notes' => $request->rental_notes,
                        'created_by' => Auth::id()
                    ]);
                } else {
                    $rentalHistory = $currentRental;
                }
            } else {
                $updateData['is_rented'] = false;
                $updateData['rent_start_date'] = null;
                $updateData['rent_end_date'] = null;
                $updateData['rented_by'] = null;
                $updateData['status'] = 'available';
            }

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
                foreach ($request->file('simbada_documents') as $index => $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $fileSize = $file->getSize();
                        $path = $file->store('halte-documents', 'public');

                        HalteDocument::create([
                            'halte_id' => $halte->id,
                            'document_type' => 'simbada',
                            'document_name' => $originalName,
                            'document_path' => $path,
                            'file_type' => $extension,
                            'file_size' => $fileSize,
                            'description' => $request->simbada_document_descriptions[$index] ?? null,
                            'uploaded_by' => Auth::id()
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error uploading SIMBADA document: ' . $e->getMessage());
                    }
                }
            }

            // Handle rental document uploads
            if ($rentalHistory && $request->hasFile('rental_documents')) {
                foreach ($request->file('rental_documents') as $index => $file) {
                    try {
                        $originalName = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        $fileSize = $file->getSize();
                        $path = $file->store('rental-documents', 'public');

                        RentalDocument::create([
                            'rental_history_id' => $rentalHistory->id,
                            'document_name' => $originalName,
                            'document_path' => $path,
                            'file_type' => $extension,
                            'file_size' => $fileSize,
                            'description' => $request->rental_document_descriptions[$index] ?? null,
                            'uploaded_by' => Auth::id()
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error uploading rental document: ' . $e->getMessage());
                    }
                }
            }

            $redirectParams = [];
            if ($request->session()->has('halte_sort')) {
                $redirectParams = $request->session()->get('halte_sort');
            }

            return redirect()->route('admin.haltes.index', $redirectParams)->with('success', 'Halte berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Error updating halte: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal mengupdate halte: ' . $e->getMessage());
        }
    }

    /**
     * Delete halte
     */
    public function halteDestroy($id)
    {
        try {
            $halte = Halte::findOrFail($id);

            foreach ($halte->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo_path)) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
            }

            $halte->delete();

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
     * Delete photo
     */
    public function deletePhoto($id)
    {
        try {
            $photo = HaltePhoto::findOrFail($id);
            $halteId = $photo->halte_id;
            $wasPrimary = $photo->is_primary;

            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }

            $photo->delete();

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
     * Set primary photo
     */
    public function setPrimaryPhoto($id)
    {
        try {
            $photo = HaltePhoto::findOrFail($id);

            HaltePhoto::where('halte_id', $photo->halte_id)
                     ->where('id', '!=', $photo->id)
                     ->update(['is_primary' => false]);

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
     * Rental history list
     */
    public function rentalHistory(Request $request)
    {
        $query = RentalHistory::with(['halte', 'creator', 'documents'])
                             ->orderBy('created_at', 'desc');

        if ($request->filled('start_date')) {
            $query->where('rent_start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('rent_end_date', '<=', $request->end_date);
        }

        if ($request->filled('halte_id')) {
            $query->where('halte_id', $request->halte_id);
        }

        if ($request->filled('rented_by')) {
            $query->where('rented_by', 'LIKE', '%' . $request->rented_by . '%');
        }

        $histories = $query->paginate(15);
        $haltes = Halte::orderBy('name')->get();

        $histories->appends($request->query());

        return view('admin.rentals.index', compact('histories', 'haltes'));
    }

    /**
     * Reports dashboard
     */
    public function reports(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

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

        $revenueChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyStats->firstWhere('month', $i);
            $revenueChart[] = [
                'month' => Carbon::create()->month($i)->format('M'),
                'revenue' => $monthData ? $monthData->total_revenue : 0,
                'rentals' => $monthData ? $monthData->total_rentals : 0
            ];
        }

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
     * Generate PDF Report
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
        } else {
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

        return view('admin.reports.pdf', compact('rentals', 'summary', 'reportTitle'));
    }
}
