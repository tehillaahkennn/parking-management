<?php
namespace App\Http\Controllers;

use App\Models\ParkingRecord;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller

{
    public function index()
    {
        $totalRecords = ParkingRecord::count();
        $completedRecords = ParkingRecord::where('status', 'Completed')->count();
        $currentlyParked = ParkingRecord::where('status', 'Parked')->count();
        $totalRevenue = Payment::sum('amount');

        return view('reports.index', compact(
            'totalRecords',
            'completedRecords',
            'currentlyParked',
            'totalRevenue'
        ));
    }

    public function parking(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $records = ParkingRecord::with(['vehicle', 'parkingSlot'])
            ->when($validated['date_from'] ?? null, function ($query, $date) {
                $query->where('entry_time', '>=', $date . ' 00:00:00');
            })
            ->when($validated['date_to'] ?? null, function ($query, $date) {
                $query->where('entry_time', '<', date('Y-m-d', strtotime($date . ' +1 day')));
            })
            ->orderByDesc('entry_time')
            ->paginate(15)
            ->withQueryString();

        return view('reports.parking', compact('records', 'validated'));
    }

    public function payments(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Payment::with([
            'parkingRecord.vehicle',
            'parkingRecord.parkingSlot'
        ])
            ->when($validated['date_from'] ?? null, function ($query, $date) {
                $query->where('paid_at', '>=', $date . ' 00:00:00');
            })
            ->when($validated['date_to'] ?? null, function ($query, $date) {
                $query->where('paid_at', '<', date('Y-m-d', strtotime($date . ' +1 day')));
            });

        $totalRevenue = (clone $query)->sum('amount');

        $payments = $query->orderByDesc('paid_at')
            ->paginate(15)
            ->withQueryString();

        return view('reports.payments', compact(
            'payments',
            'totalRevenue',
            'validated'
        ));
    }
}
