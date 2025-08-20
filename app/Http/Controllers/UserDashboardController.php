<?php
// app/Http/Controllers/UserDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'user']);
    }

    /**
     * Show user dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get statistics
        $totalHaltes = Halte::count();
        $availableHaltes = Halte::whereNull('rent_start_date')
            ->orWhere('rent_end_date', '<', now())
            ->count();
        $rentedHaltes = Halte::whereNotNull('rent_start_date')
            ->where(function($query) {
                $query->whereNull('rent_end_date')
                    ->orWhere('rent_end_date', '>=', now());
            })
            ->count();

        // Get recent haltes (last 6)
        $recentHaltes = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }])->orderBy('created_at', 'desc')->take(6)->get();

        return view('user.dashboard', compact('user', 'totalHaltes', 'availableHaltes', 'rentedHaltes', 'recentHaltes'));
    }

    /**
     * Show all haltes list
     */
    public function haltesList(Request $request)
    {
        $query = Halte::with(['photos' => function($q) {
            $q->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }]);

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('address', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                $query->where(function($q) {
                    $q->whereNull('rent_start_date')
                      ->orWhere('rent_end_date', '<', now());
                });
            } elseif ($request->status === 'rented') {
                $query->whereNotNull('rent_start_date')
                      ->where(function($q) {
                          $q->whereNull('rent_end_date')
                            ->orWhere('rent_end_date', '>=', now());
                      });
            }
        }

        $haltes = $query->orderBy('name', 'asc')->paginate(12);
        $haltes->appends($request->query());

        return view('user.haltes.index', compact('haltes'));
    }

    /**
     * Show halte detail
     */
    public function halteDetail($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }])->findOrFail($id);

        return view('user.haltes.detail', compact('halte'));
    }

    /**
     * Show map view
     */
    public function mapView()
    {
        // Get all haltes with photos
        $haltes = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }])->get();

        // Transform data for JavaScript
        $haltesData = $haltes->map(function ($halte) {
            $isCurrentlyRented = $halte->isCurrentlyRented();

            // Get primary photo or first available photo
            $primaryPhotoUrl = $this->getPrimaryPhotoUrl($halte);

            return [
                'id' => $halte->id,
                'name' => $halte->name,
                'description' => $halte->description,
                'latitude' => (float) $halte->latitude,
                'longitude' => (float) $halte->longitude,
                'address' => $halte->address,
                'rental_status' => $isCurrentlyRented ? 'rented' : 'available',
                'is_rented' => $isCurrentlyRented,
                'rent_start_date' => $halte->rent_start_date ? $halte->rent_start_date->format('d/m/Y') : null,
                'rent_end_date' => $halte->rent_end_date ? $halte->rent_end_date->format('d/m/Y') : null,
                'rented_by' => $halte->rented_by,
                'simbada_registered' => $halte->simbada_registered,
                'simbada_number' => $halte->simbada_number,
                'primary_photo' => $primaryPhotoUrl,
                'photos' => $halte->photos->map(function($photo) {
                    return asset('storage/' . $photo->photo_path);
                })->toArray()
            ];
        });

        return view('user.map', compact('haltesData'));
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address
            ];

            // Update password if provided
            if ($request->filled('current_password') && $request->filled('new_password')) {
                if (!\Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini tidak benar']);
                }
                $updateData['password'] = \Hash::make($request->new_password);
            }

            $user->update($updateData);

            return redirect()->route('user.profile')
                ->with('success', 'Profil berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate profil');
        }
    }

    /**
     * Helper method to get primary photo URL
     */
    private function getPrimaryPhotoUrl($halte)
    {
        // First try to get primary photo
        $primaryPhoto = $halte->photos->where('is_primary', true)->first();

        if ($primaryPhoto && file_exists(storage_path('app/public/' . $primaryPhoto->photo_path))) {
            return asset('storage/' . $primaryPhoto->photo_path);
        }

        // If no primary photo, get first available photo
        $firstPhoto = $halte->photos->first();
        if ($firstPhoto && file_exists(storage_path('app/public/' . $firstPhoto->photo_path))) {
            return asset('storage/' . $firstPhoto->photo_path);
        }

        // Return default image if no photos or files don't exist
        return asset('images/halte-default.png');
    }
}
