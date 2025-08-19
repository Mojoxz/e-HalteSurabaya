<?php
// app/Http/Controllers/AdminController.php - FIXED VERSION

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;
use App\Models\HaltePhoto;
use App\Models\RentalHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

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

        return view('admin.dashboard', compact(
            'totalHaltes',
            'availableHaltes',
            'rentedHaltes',
            'totalRevenue'
        ));
    }

    /**
     * List all haltes - FIXED WITH PROPER EAGER LOADING AND FILTERING
     */
    public function halteList(Request $request)
    {
        $query = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }, 'rentalHistories']);

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

        $haltes = $query->orderBy('created_at', 'desc')->paginate(10);

        // Preserve query parameters in pagination links
        $haltes->appends($request->query());

        return view('admin.haltes.index', compact('haltes'));
    }

    /**
     * Show create halte form
     */
    public function halteCreate()
    {
        return view('admin.haltes.create');
    }

    /**
     * Store new halte - FIXED RENTAL STATUS LOGIC
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
            'rent_start_date' => 'nullable|date',
            'rent_end_date' => 'nullable|date|after:rent_start_date',
            'rented_by' => 'nullable|string|max:255',
            'rental_cost' => 'nullable|numeric|min:0',
            'rental_notes' => 'nullable|string'
        ]);

        // FIXED: Proper rental status logic
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

        // Handle rental information with proper validation
        if ($isRented && $hasValidRentalDates) {
            $startDate = Carbon::parse($request->rent_start_date);
            $endDate = Carbon::parse($request->rent_end_date);
            $now = Carbon::now();

            $halteData['is_rented'] = true;
            $halteData['rent_start_date'] = $startDate;
            $halteData['rent_end_date'] = $endDate;
            $halteData['rented_by'] = $request->rented_by;

            // Set proper status based on dates
            if ($now->between($startDate, $endDate)) {
                $halteData['status'] = 'rented';
            } elseif ($now->isBefore($startDate)) {
                $halteData['status'] = 'available'; // Future rental
            } else {
                $halteData['status'] = 'available'; // Expired rental
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
        if ($isRented && $hasValidRentalDates) {
            RentalHistory::create([
                'halte_id' => $halte->id,
                'rented_by' => $request->rented_by,
                'rent_start_date' => $request->rent_start_date,
                'rent_end_date' => $request->rent_end_date,
                'rental_cost' => $request->rental_cost ?? 0,
                'notes' => $request->rental_notes,
                'created_by' => Auth::id()
            ]);
        }

        // Handle photo uploads - FIXED
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

        return redirect()->route('admin.haltes.index')->with('success', 'Halte berhasil ditambahkan');
    }

    /**
     * Show halte details
     */
    public function halteShow($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc');
        }, 'rentalHistories'])->findOrFail($id);

        return view('admin.haltes.show', compact('halte'));
    }

    /**
     * Show edit halte form
     */
    public function halteEdit($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc');
        }])->findOrFail($id);

        return view('admin.haltes.edit', compact('halte'));
    }

    /**
     * Update halte - COMPLETELY FIXED
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
                'rent_start_date' => 'nullable|date',
                'rent_end_date' => 'nullable|date|after:rent_start_date',
                'rented_by' => 'nullable|string|max:255',
                'rental_cost' => 'nullable|numeric|min:0',
                'rental_notes' => 'nullable|string'
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

                // Create rental history only if this is a new rental or rental info changed
                $currentRental = $halte->rentalHistories()->latest()->first();
                $shouldCreateNewHistory = !$currentRental ||
                                          $currentRental->rent_start_date->format('Y-m-d') != $startDate->format('Y-m-d') ||
                                          $currentRental->rent_end_date->format('Y-m-d') != $endDate->format('Y-m-d') ||
                                          $currentRental->rented_by != $request->rented_by;

                if ($shouldCreateNewHistory) {
                    RentalHistory::create([
                        'halte_id' => $halte->id,
                        'rented_by' => $request->rented_by,
                        'rent_start_date' => $startDate,
                        'rent_end_date' => $endDate,
                        'rental_cost' => $request->rental_cost ?? 0,
                        'notes' => $request->rental_notes,
                        'created_by' => Auth::id()
                    ]);
                }
            } else {
                $updateData['is_rented'] = false;
                $updateData['rent_start_date'] = null;
                $updateData['rent_end_date'] = null;
                $updateData['rented_by'] = null;
                $updateData['status'] = 'available';
            }

            // FIXED: Update halte data
            $halte->update($updateData);

            // Handle new photo uploads - FIXED
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $photo) {
                    try {
                        $path = $photo->store('halte-photos', 'public');

                        HaltePhoto::create([
                            'halte_id' => $halte->id,
                            'photo_path' => $path,
                            'description' => $request->photo_descriptions[$index] ?? null,
                            'is_primary' => false // New photos are not primary by default
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error uploading photo: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('admin.haltes.index')->with('success', 'Halte berhasil diupdate');

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

            // Delete photos from storage
            foreach ($halte->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo_path)) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
            }

            $halte->delete();

            return redirect()->route('admin.haltes.index')->with('success', 'Halte berhasil dihapus');
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
     * Rental history list
     */
    public function rentalHistory()
    {
        $histories = RentalHistory::with(['halte', 'creator'])
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(15);

        return view('admin.rentals.index', compact('histories'));
    }
}
