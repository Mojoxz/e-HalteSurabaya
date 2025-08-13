<?php
// app/Http/Controllers/HomeController.php

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
        // Get all haltes for initial map display
        $haltes = Halte::with(['photos' => function($query) {
            $query->where('is_primary', true)->orWhere(function($q) {
                $q->whereDoesntHave('halte', function($subQ) {
                    $subQ->whereHas('photos', function($photoQ) {
                        $photoQ->where('is_primary', true);
                    });
                });
            })->limit(1);
        }])->get();

        // Transform data for JavaScript
        $haltesData = $haltes->map(function ($halte) {
            $isCurrentlyRented = $halte->isCurrentlyRented();

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
                'primary_photo' => $halte->primary_photo_url,
                'photos' => $halte->photo_urls
            ];
        });

        // Statistics for display
        $statistics = [
            'total' => $haltes->count(),
            'available' => $haltes->where('rental_status', 'available')->count(),
            'rented' => $haltes->where('rental_status', 'rented')->count(),
        ];

        return view('home', compact('haltesData', 'statistics'));
    }

    /**
     * Show halte details (for AJAX requests)
     */
    public function showHalte($id)
    {
        $halte = Halte::with(['photos', 'rentalHistories'])->find($id);

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
                'photos' => $halte->photos->map(function($photo) {
                    return [
                        'url' => $photo->photo_url,
                        'description' => $photo->description,
                        'is_primary' => $photo->is_primary
                    ];
                })
            ]
        ]);
    }
}
