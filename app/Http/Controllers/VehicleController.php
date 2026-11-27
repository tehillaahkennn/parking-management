<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\AuditLogger;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Display vehicles and search by plate number or owner.
    public function index(Request $request)
    {
        $search = $request->input('search');

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

    // Show the registration form.
    public function create()
    {
        return view('vehicles.create');
    }

    // Save a new vehicle and record the audit log.
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

    // Show the edit form.
    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    // Update an existing vehicle and record the audit log.
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $vehicle->id,
            'owner_name' => 'required|string|max:100',
            'vehicle_type' => 'required|in:Motorcycle,Car,Van',
        ]);

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

    // Delete only vehicles that have no parking history.
    public function destroy(Vehicle $vehicle)
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
            return redirect()
                ->route('vehicles.index')
                ->with(
                    'error',
                    'The vehicle could not be deleted because it is referenced by another record.'
                );
        }

        AuditLogger::log(
            request(),
            'DELETE',
            'Deleted vehicle: ' . $plateNumber
        );

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
