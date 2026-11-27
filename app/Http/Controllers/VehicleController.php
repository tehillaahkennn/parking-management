<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\AuditLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display vehicles with search and pagination.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
        ]);

        $search = $validated['search'] ?? null;

        $vehicles = Vehicle::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('plate_number', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%");
                });
            })
            ->withCount('parkingRecords')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vehicles.index', compact('vehicles', 'search'));
    }

    /**
     * Show the vehicle registration form.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Register a vehicle and create an audit log.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'owner_name' => 'required|string|max:100',
            'vehicle_type' => 'required|in:Motorcycle,Car,Van',
        ]);

        $vehicle = Vehicle::create($validated);

        AuditLogger::log(
            $request,
            'CREATE',
            'Registered vehicle: ' . $vehicle->plate_number
        );

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle registered successfully.');
    }

    /**
     * Show the vehicle edit form.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update a vehicle while protecting its parking history.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => [
                'required',
                'string',
                'max:20',
                'unique:vehicles,plate_number,' . $vehicle->id,
            ],
            'owner_name' => 'required|string|max:100',
            'vehicle_type' => 'required|in:Motorcycle,Car,Van',
        ]);

        // Prevent changing vehicle type if parking history exists.
        if (
            $validated['vehicle_type'] !== $vehicle->vehicle_type
            && $vehicle->parkingRecords()->exists()
        ) {
            return back()
                ->withErrors([
                    'vehicle_type' => 'Vehicle type cannot be changed because this vehicle has parking history.',
                ])
                ->withInput();
        }

        $vehicle->update($validated);

        AuditLogger::log(
            $request,
            'UPDATE',
            'Updated vehicle: ' . $vehicle->plate_number
        );

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Delete a vehicle only when it has no parking history.
     */
    public function destroy(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->parkingRecords()->exists()) {
            return redirect()
                ->route('vehicles.index')
                ->with(
                    'error',
                    'This vehicle cannot be deleted because it has parking records.'
                );
        }

        $plateNumber = $vehicle->plate_number;

        try {
            $vehicle->delete();
        } catch (QueryException $e) {
            report($e);

            return redirect()
                ->route('vehicles.index')
                ->with(
                    'error',
                    'The vehicle could not be deleted because it is referenced by another record.'
                );
        }

        AuditLogger::log(
            $request,
            'DELETE',
            'Deleted vehicle: ' . $plateNumber
        );

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
