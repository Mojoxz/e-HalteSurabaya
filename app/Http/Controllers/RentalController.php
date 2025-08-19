<?php
// app/Http/Controllers/Admin/RentalController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Halte;
use App\Models\RentalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Display rental histories
     */
    public function index(Request $request)
    {
        $query = RentalHistory::with(['halte', 'creator']);

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'expired':
                    $query->expired();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'completed':
                    $query->completed();
                    break;
                case 'cancelled':
                    $query->cancelled();
                    break;
            }
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by halte
        if ($request->filled('halte_id')) {
            $query->forHalte($request->halte_id);
        }

        // Filter by renter
        if ($request->filled('renter')) {
            $query->forRenter($request->renter);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $histories = $query->paginate(15);
        $haltes = Halte::select('id', 'name')->get();

        // Statistics
        $stats = [
            'total' => RentalHistory::count(),
            'active' => RentalHistory::active()->count(),
            'expired' => RentalHistory::expired()->count(),
            'upcoming' => RentalHistory::upcoming()->count(),
            'total_income' => RentalHistory::paid()->sum('rental_cost'),
            'pending_income' => RentalHistory::pendingPayment()->sum('rental_cost')
        ];

        return view('admin.rentals.index', compact('histories', 'haltes', 'stats'));
    }

    /**
     * Show rental history details
     */
    public function show(RentalHistory $rental)
    {
        $rental->load(['halte', 'creator']);
        return view('admin.rentals.show', compact('rental'));
    }

    /**
     * Show form to create new rental
     */
    public function create()
    {
        $haltes = Halte::available()->select('id', 'name', 'address')->get();
        return view('admin.rentals.create', compact('haltes'));
    }

    /**
     * Store new rental
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'halte_id' => 'required|exists:haltes,id',
            'rented_by' => 'required|string|max:255',
            'rent_start_date' => 'required|date|after_or_equal:today',
            'rent_end_date' => 'required|date|after:rent_start_date',
            'rental_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,partial,paid',
            'notes' => 'nullable|string'
        ]);

        $halte = Halte::findOrFail($validated['halte_id']);

        // Check if halte is available for the requested period
        $conflictingRentals = $halte->rentalHistories()
            ->where('status', 'active')
            ->where(function($q) use ($validated) {
                $q->whereBetween('rent_start_date', [$validated['rent_start_date'], $validated['rent_end_date']])
                  ->orWhereBetween('rent_end_date', [$validated['rent_start_date'], $validated['rent_end_date']])
                  ->orWhere(function($subQ) use ($validated) {
                      $subQ->where('rent_start_date', '<=', $validated['rent_start_date'])
                           ->where('rent_end_date', '>=', $validated['rent_end_date']);
                  });
            })
            ->exists();

        if ($conflictingRentals) {
            return back()->withErrors(['rent_start_date' => 'Halte sudah disewa pada periode tersebut.'])->withInput();
        }

        // Create rental history
        $validated['created_by'] = Auth::id();
        $history = $halte->createRentalHistory($validated);

        return redirect()->route('admin.rentals.index')
                        ->with('success', 'Rental berhasil ditambahkan.');
    }

    /**
     * Show form to edit rental
     */
    public function edit(RentalHistory $rental)
    {
        $haltes = Halte::select('id', 'name', 'address')->get();
        return view('admin.rentals.edit', compact('rental', 'haltes'));
    }

    /**
     * Update rental
     */
    public function update(Request $request, RentalHistory $rental)
    {
        $validated = $request->validate([
            'halte_id' => 'required|exists:haltes,id',
            'rented_by' => 'required|string|max:255',
            'rent_start_date' => 'required|date',
            'rent_end_date' => 'required|date|after:rent_start_date',
            'rental_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,partial,paid',
            'status' => 'required|in:active,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        // Check conflicts if dates changed
        if ($validated['halte_id'] != $rental->halte_id ||
            $validated['rent_start_date'] != $rental->rent_start_date->format('Y-m-d') ||
            $validated['rent_end_date'] != $rental->rent_end_date->format('Y-m-d')) {

            $conflictingRentals = RentalHistory::where('halte_id', $validated['halte_id'])
                ->where('id', '!=', $rental->id)
                ->where('status', 'active')
                ->where(function($q) use ($validated) {
                    $q->whereBetween('rent_start_date', [$validated['rent_start_date'], $validated['rent_end_date']])
                      ->orWhereBetween('rent_end_date', [$validated['rent_start_date'], $validated['rent_end_date']])
                      ->orWhere(function($subQ) use ($validated) {
                          $subQ->where('rent_start_date', '<=', $validated['rent_start_date'])
                               ->where('rent_end_date', '>=', $validated['rent_end_date']);
                      });
                })
                ->exists();

            if ($conflictingRentals) {
                return back()->withErrors(['rent_start_date' => 'Halte sudah disewa pada periode tersebut.'])->withInput();
            }
        }

        $rental->update($validated);

        // Update halte status if needed
        if ($validated['halte_id'] == $rental->halte_id) {
            $rental->halte->updateRentalStatus();
        } else {
            // If halte changed, update both old and new halte
            $rental->halte->updateRentalStatus();
            Halte::find($validated['halte_id'])->updateRentalStatus();
        }

        return redirect()->route('admin.rentals.index')
                        ->with('success', 'Rental berhasil diupdate.');
    }

    /**
     * Delete rental
     */
    public function destroy(RentalHistory $rental)
    {
        $halte = $rental->halte;
        $rental->delete();

        // Update halte status
        $halte->updateRentalStatus();

        return redirect()->route('admin.rentals.index')
                        ->with('success', 'Rental berhasil dihapus.');
    }

    /**
     * Mark rental as completed
     */
    public function complete(Request $request, RentalHistory $rental)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $rental->markAsCompleted($request->notes);
        $rental->halte->updateRentalStatus();

        return back()->with('success', 'Rental berhasil diselesaikan.');
    }

    /**
     * Cancel rental
     */
    public function cancel(Request $request, RentalHistory $rental)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $rental->markAsCancelled($request->reason);
        $rental->halte->updateRentalStatus();

        return back()->with('success', 'Rental berhasil dibatalkan.');
    }

    /**
     * Update payment status
     */
    public function updatePayment(Request $request, RentalHistory $rental)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,partial,paid',
            'notes' => 'nullable|string'
        ]);

        $rental->updatePaymentStatus($request->payment_status, $request->notes);

        return back()->with('success', 'Status pembayaran berhasil diupdate.');
    }

    /**
     * Get rental data for AJAX
     */
    public function getRentalData(Request $request)
    {
        if ($request->has('halte_id')) {
            $halte = Halte::with(['rentalHistories' => function($q) {
                $q->orderBy('created_at', 'desc')->limit(10);
            }])->findOrFail($request->halte_id);

            return response()->json([
                'halte' => $halte,
                'current_rental' => $halte->currentRental,
                'rental_status' => $halte->rental_status,
                'recent_histories' => $halte->rentalHistories
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Export rental histories to CSV
     */
    public function export(Request $request)
    {
        $query = RentalHistory::with(['halte', 'creator']);

        // Apply same filters as index
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->active();
                    break;
                case 'expired':
                    $query->expired();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'completed':
                    $query->completed();
                    break;
                case 'cancelled':
                    $query->cancelled();
                    break;
            }
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('halte_id')) {
            $query->forHalte($request->halte_id);
        }

        if ($request->filled('renter')) {
            $query->forRenter($request->renter);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->dateRange($request->start_date, $request->end_date);
        }

        $histories = $query->orderBy('created_at', 'desc')->get();

        $filename = 'rental_histories_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($histories) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Halte',
                'Penyewa',
                'Tanggal Mulai',
                'Tanggal Berakhir',
                'Biaya Sewa',
                'Status',
                'Status Pembayaran',
                'Durasi (Hari)',
                'Catatan',
                'Dibuat Oleh',
                'Tanggal Dibuat'
            ]);

            foreach ($histories as $history) {
                fputcsv($file, [
                    $history->id,
                    $history->halte->name,
                    $history->rented_by,
                    $history->rent_start_date->format('d/m/Y'),
                    $history->rent_end_date->format('d/m/Y'),
                    $history->rental_cost,
                    $history->rental_status,
                    ucfirst($history->payment_status),
                    $history->duration,
                    $history->notes,
                    $history->creator->name ?? 'N/A',
                    $history->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $stats = [
            'total_rentals' => RentalHistory::count(),
            'active_rentals' => RentalHistory::active()->count(),
            'expired_rentals' => RentalHistory::expired()->count(),
            'upcoming_rentals' => RentalHistory::upcoming()->count(),
            'completed_rentals' => RentalHistory::completed()->count(),
            'cancelled_rentals' => RentalHistory::cancelled()->count(),
            'total_income' => RentalHistory::paid()->sum('rental_cost'),
            'pending_income' => RentalHistory::pendingPayment()->sum('rental_cost'),
            'monthly_income' => RentalHistory::paid()
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('rental_cost'),
            'yearly_income' => RentalHistory::paid()
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('rental_cost')
        ];

        // Monthly rental count for chart
        $monthlyRentals = RentalHistory::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyRentals[$i] ?? 0;
        }

        // Top rented haltes
        $topHaltes = Halte::withCount('rentalHistories')
            ->orderBy('rental_histories_count', 'desc')
            ->limit(5)
            ->get(['id', 'name'])
            ->map(function($halte) {
                return [
                    'name' => $halte->name,
                    'rentals_count' => $halte->rental_histories_count
                ];
            });

        return response()->json([
            'stats' => $stats,
            'monthly_data' => $monthlyData,
            'top_haltes' => $topHaltes
        ]);
    }
}
