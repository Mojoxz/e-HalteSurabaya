<?php
// app/Http/Controllers/ApiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Halte;
use Carbon\Carbon;

class ApiController extends Controller
{
    /**
     * Get all haltes for map display
     */
    public function getHaltes()
    {
        $haltes = Halte::with(['photos' => function($query) {
            $query->where('is_primary', true)->orWhere(function($q) {
                $q->whereDoesntHave('halte', function($subQ) {
                    $subQ->whereHas('photos', function($photoQ) {
                        $photoQ->where('is_primary', true);
                    });
                });
            })->limit(1);
        }])->get();

        $response = $haltes->map(function ($halte) {
            $isCurrentlyRented = $halte->isCurrentlyRented();
            $status = $isCurrentlyRented ? 'rented' : 'available';

            return [
                'id' => $halte->id,
                'name' => $halte->name,
                'description' => $halte->description,
                'latitude' => (float) $halte->latitude,
                'longitude' => (float) $halte->longitude,
                'address' => $halte->address,
                'status' => $status,
                'rental_status' => $status, // For map marker color
                'is_rented' => $isCurrentlyRented,
                'rent_start_date' => $halte->rent_start_date ? $halte->rent_start_date->format('Y-m-d') : null,
                'rent_end_date' => $halte->rent_end_date ? $halte->rent_end_date->format('Y-m-d') : null,
                'rented_by' => $halte->rented_by,
                'simbada_registered' => $halte->simbada_registered,
                'simbada_number' => $halte->simbada_number,
                'primary_photo' => $halte->primary_photo_url,
                'photos' => $halte->photo_urls,
                'created_at' => $halte->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $halte->updated_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $response,
            'total' => $haltes->count()
        ]);
    }

    /**
     * Get single halte details
     */
    public function getHalte($id)
    {
        $halte = Halte::with(['photos', 'rentalHistories'])->find($id);

        if (!$halte) {
            return response()->json([
                'status' => 'error',
                'message' => 'Halte tidak ditemukan'
            ], 404);
        }

        $isCurrentlyRented = $halte->isCurrentlyRented();
        $status = $isCurrentlyRented ? 'rented' : 'available';

        $response = [
            'id' => $halte->id,
            'name' => $halte->name,
            'description' => $halte->description,
            'latitude' => (float) $halte->latitude,
            'longitude' => (float) $halte->longitude,
            'address' => $halte->address,
            'status' => $status,
            'rental_status' => $status,
            'is_rented' => $isCurrentlyRented,
            'rent_start_date' => $halte->rent_start_date ? $halte->rent_start_date->format('Y-m-d') : null,
            'rent_end_date' => $halte->rent_end_date ? $halte->rent_end_date->format('Y-m-d') : null,
            'rented_by' => $halte->rented_by,
            'simbada_registered' => $halte->simbada_registered,
            'simbada_number' => $halte->simbada_number,
            'photos' => $halte->photos->map(function($photo) {
                return [
                    'id' => $photo->id,
                    'url' => $photo->photo_url,
                    'description' => $photo->description,
                    'is_primary' => $photo->is_primary
                ];
            }),
            'rental_histories' => $halte->rentalHistories->map(function($history) {
                return [
                    'id' => $history->id,
                    'rented_by' => $history->rented_by,
                    'rent_start_date' => $history->rent_start_date->format('Y-m-d'),
                    'rent_end_date' => $history->rent_end_date->format('Y-m-d'),
                    'rental_cost' => $history->rental_cost,
                    'notes' => $history->notes,
                    'created_at' => $history->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'created_at' => $halte->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $halte->updated_at->format('Y-m-d H:i:s')
        ];

        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }

    /**
     * Get haltes statistics
     */
    public function getStatistics()
    {
        $total = Halte::count();
        $available = Halte::available()->count();
        $rented = Halte::rented()->count();
        $simbadaRegistered = Halte::where('simbada_registered', true)->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_haltes' => $total,
                'available_haltes' => $available,
                'rented_haltes' => $rented,
                'simbada_registered' => $simbadaRegistered,
                'availability_percentage' => $total > 0 ? round(($available / $total) * 100, 2) : 0,
                'occupancy_percentage' => $total > 0 ? round(($rented / $total) * 100, 2) : 0
            ]
        ]);
    }

    /**
     * Search haltes
     */
    public function searchHaltes(Request $request)
    {
        $query = Halte::with(['photos' => function($query) {
            $query->where('is_primary', true)->orWhere(function($q) {
                $q->whereDoesntHave('halte', function($subQ) {
                    $subQ->whereHas('photos', function($photoQ) {
                        $photoQ->where('is_primary', true);
                    });
                });
            })->limit(1);
        }]);

        // Filter by name or address
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'available') {
                $query->available();
            } elseif ($request->status === 'rented') {
                $query->rented();
            }
        }

        // Filter by simbada registration
        if ($request->has('simbada_registered')) {
            $query->where('simbada_registered', $request->simbada_registered);
        }

        $haltes = $query->get();

        $response = $haltes->map(function ($halte) {
            $isCurrentlyRented = $halte->isCurrentlyRented();
            $status = $isCurrentlyRented ? 'rented' : 'available';

            return [
                'id' => $halte->id,
                'name' => $halte->name,
                'description' => $halte->description,
                'latitude' => (float) $halte->latitude,
                'longitude' => (float) $halte->longitude,
                'address' => $halte->address,
                'status' => $status,
                'rental_status' => $status,
                'is_rented' => $isCurrentlyRented,
                'rent_start_date' => $halte->rent_start_date ? $halte->rent_start_date->format('Y-m-d') : null,
                'rent_end_date' => $halte->rent_end_date ? $halte->rent_end_date->format('Y-m-d') : null,
                'rented_by' => $halte->rented_by,
                'simbada_registered' => $halte->simbada_registered,
                'simbada_number' => $halte->simbada_number,
                'primary_photo' => $halte->primary_photo_url,
                'created_at' => $halte->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $response,
            'total' => $haltes->count(),
            'filters_applied' => [
                'search' => $request->search,
                'status' => $request->status,
                'simbada_registered' => $request->simbada_registered
            ]
        ]);
    }
}
