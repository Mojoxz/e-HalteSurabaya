<?php
// app/Http/Controllers/AdminController.php - UPDATED METHODS

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;
use App\Models\HaltePhoto;
use App\Models\RentalHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
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

// app/Http/Controllers/AdminController.php - PERBAIKI METHOD INI

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
     * Store new halte - FIXED PHOTO HANDLING
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
        if ($request->has('is_rented') && $request->filled('rent_start_date') && $request->filled('rent_end_date')) {
            $halteData['is_rented'] = true;
            $halteData['rent_start_date'] = $request->rent_start_date;
            $halteData['rent_end_date'] = $request->rent_end_date;
            $halteData['rented_by'] = $request->rented_by;
            $halteData['status'] = 'rented';
        } else {
            $halteData['is_rented'] = false;
            $halteData['rent_start_date'] = null;
            $halteData['rent_end_date'] = null;
            $halteData['rented_by'] = null;
            $halteData['status'] = 'available';
        }

        $halte = Halte::create($halteData);

        // Create rental history if halte is rented
        if ($halte->is_rented) {
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
                        'is_primary' => $index === 0 // First photo as primary
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error uploading photo: ' . $e->getMessage());
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
     * Update halte - FIXED
     */
    public function halteUpdate(Request $request, $id)
    {
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
        if ($request->has('is_rented') && $request->filled('rent_start_date') && $request->filled('rent_end_date')) {
            $updateData['is_rented'] = true;
            $updateData['rent_start_date'] = $request->rent_start_date;
            $updateData['rent_end_date'] = $request->rent_end_date;
            $updateData['rented_by'] = $request->rented_by;
            $updateData['status'] = 'rented';

            // Create rental history only if this is a new rental or rental info changed
            $currentRental = $halte->rentalHistories()->latest()->first();
            $shouldCreateNewHistory = !$currentRental ||
                                      $currentRental->rent_start_date != $request->rent_start_date ||
                                      $currentRental->rent_end_date != $request->rent_end_date ||
                                      $currentRental->rented_by != $request->rented_by;

            if ($shouldCreateNewHistory) {
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
        } else {
            $updateData['is_rented'] = false;
            $updateData['rent_start_date'] = null;
            $updateData['rent_end_date'] = null;
            $updateData['rented_by'] = null;
            $updateData['status'] = 'available';
        }

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
                    \Log::error('Error uploading photo: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.haltes.index')->with('success', 'Halte berhasil diupdate');
    }

    /**
     * Delete halte
     */
    public function halteDestroy($id)
    {
        $halte = Halte::findOrFail($id);

        // Delete photos from storage
        foreach ($halte->photos as $photo) {
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }
        }

        $halte->delete();

        return redirect()->route('admin.haltes.index')->with('success', 'Halte berhasil dihapus');
    }

    /**
     * Delete photo - FIXED WITH BETTER ERROR HANDLING
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

            $photo->delete();

            // If deleted photo was primary, make first available photo primary
            if ($wasPrimary) {
                $firstPhoto = HaltePhoto::where('halte_id', $halteId)->first();
                if ($firstPhoto) {
                    $firstPhoto->update(['is_primary' => true]);
                }
            }

            return back()->with('success', 'Foto berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error deleting photo: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus foto');
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
            HaltePhoto::where('halte_id', $photo->halte_id)->update(['is_primary' => false]);

            // Set this photo as primary
            $photo->update(['is_primary' => true]);

            return back()->with('success', 'Foto utama berhasil diatur');
        } catch (\Exception $e) {
            \Log::error('Error setting primary photo: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengatur foto utama');
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
