<?php
// app/Http/Controllers/HomeController.php - IMPROVED VERSION WITH GALLERY PAGE

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Show the main user interface with map
     */
    public function index()
    {
        // Get all haltes with photos - SIMPLIFIED AND FIXED
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

        // Calculate statistics correctly
        $totalHaltes = $haltes->count();
        $availableCount = $haltes->filter(function($halte) {
            return !$halte->isCurrentlyRented();
        })->count();
        $rentedCount = $haltes->filter(function($halte) {
            return $halte->isCurrentlyRented();
        })->count();

        $statistics = [
            'total' => $totalHaltes,
            'available' => $availableCount,
            'rented' => $rentedCount,
        ];

        return view('home', compact('haltesData', 'statistics'));
    }

    /**
     * Show gallery page with all haltes (NEW METHOD)
     */
    public function gallery()
    {
        $haltes = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }])->get();

        return view('gallery', compact('haltes'));
    }

    /**
     * Show halte details page
     */
    public function detail($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }, 'rentalHistories' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);

        if (!$halte) {
            return redirect()->route('home')->with('error', 'Halte tidak ditemukan');
        }

        return view('halte-detail', compact('halte'));
    }

    /**
     * Show halte details (for AJAX requests)
     */
    public function showHalte($id)
    {
        $halte = Halte::with(['photos' => function($query) {
            $query->orderBy('is_primary', 'desc')->orderBy('id', 'asc');
        }, 'rentalHistories'])->find($id);

        if (!$halte) {
            return response()->json([
                'status' => 'error',
                'message' => 'Halte tidak ditemukan'
            ], 404);
        }

        $isCurrentlyRented = $halte->isCurrentlyRented();

        return response()->json([
            'status' => 'success',
            'data' => [
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
                'primary_photo' => $this->getPrimaryPhotoUrl($halte),
                'photos' => $halte->photos->map(function($photo) {
                    return [
                        'id' => $photo->id,
                        'url' => asset('storage/' . $photo->photo_path),
                        'description' => $photo->description,
                        'is_primary' => $photo->is_primary
                    ];
                })
            ]
        ]);
    }

    /**
     * Helper method to get primary photo URL
     */
    private function getPrimaryPhotoUrl($halte)
    {
        // First try to get primary photo
        $primaryPhoto = $halte->photos->where('is_primary', true)->first();

        if ($primaryPhoto && $this->fileExists($primaryPhoto->photo_path)) {
            return asset('storage/' . $primaryPhoto->photo_path);
        }

        // If no primary photo, get first available photo
        $firstPhoto = $halte->photos->first();
        if ($firstPhoto && $this->fileExists($firstPhoto->photo_path)) {
            return asset('storage/' . $firstPhoto->photo_path);
        }

        // Return default image if no photos or files don't exist
        return asset('images/halte-default.png');
    }

    /**
     * Helper method to check if file exists
     */
    private function fileExists($photoPath)
    {
        return file_exists(storage_path('app/public/' . $photoPath));
    }

    public function maps()
{
    // Get all halte data
    $haltes = Halte::with('photos')->get();
    
    // Calculate statistics
    $statistics = [
        'total' => $haltes->count(),
        'available' => $haltes->where('rental_status', 'available')->count(),
        'rented' => $haltes->where('rental_status', 'rented')->count(),
    ];
    
    // Prepare halte data for map
    $haltesData = $haltes->map(function ($halte) {
        return [
            'id' => $halte->id,
            'name' => $halte->name,
            'description' => $halte->description,
            'address' => $halte->address,
            'latitude' => (float) $halte->latitude,
            'longitude' => (float) $halte->longitude,
            'rental_status' => $halte->rental_status,
            'is_rented' => $halte->rental_status === 'rented',
            'rented_by' => $halte->rented_by,
            'rent_end_date' => $halte->rent_end_date ? $halte->rent_end_date->format('d M Y') : null,
            'simbada_registered' => $halte->simbada_registered,
            'simbada_number' => $halte->simbada_number,
            'photos' => $halte->photos->map(function ($photo) {
                return asset('storage/' . $photo->path);
            })->toArray(),
        ];
    })->toArray();
    
    return view('maps', compact('statistics', 'haltesData'));
}


}
