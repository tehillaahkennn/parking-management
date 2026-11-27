<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'Parking Staff')
            ->orderBy('name')
            ->paginate(10);

        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $staff = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'Parking Staff',
            ]);

            AuditLogger::log(
                $request,
                'CREATE',
                'Created Parking Staff account: '
                    . $staff->name . ' (' . $staff->email . ')'
            );
        });

        return redirect()
            ->route('staff.index')
            ->with('success', 'Parking Staff account created successfully.');
    }

    public function edit(User $staff)
    {
        abort_unless($staff->role === 'Parking Staff', 404);

        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        abort_unless($staff->role === 'Parking Staff', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($staff->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $staff, $request) {
            $staff->name = $validated['name'];
            $staff->email = $validated['email'];

            if (!empty($validated['password'])) {
                $staff->password = $validated['password'];
            }

            $staff->save();

            AuditLogger::log(
                $request,
                'UPDATE',
                'Updated Parking Staff account: '
                    . $staff->name . ' (' . $staff->email . ')'
            );
        });

        return redirect()
            ->route('staff.index')
            ->with('success', 'Parking Staff account updated successfully.');
    }
    
public function toggleStatus(Request $request, User $staff)
{
    abort_unless($staff->role === 'Parking Staff', 404);

    // Prevent the administrator from changing their own status.
    if ($request->user()->id === $staff->id) {
        return back()->with(
            'error',
            'You cannot change your own account status.'
        );
    }

    DB::transaction(function () use ($staff, $request) {
        $staff->is_active = ! $staff->is_active;
        $staff->save();

        AuditLogger::log(
            $request,
            $staff->is_active ? 'ACTIVATE' : 'DEACTIVATE',
            ($staff->is_active ? 'Activated' : 'Deactivated')
                . ' Parking Staff account: '
                . $staff->name . ' (' . $staff->email . ')'
        );
    });

    return redirect()
        ->route('staff.index')
        ->with('success', 'Staff account status updated successfully.');
}

    public function destroy(Request $request, User $staff)
    {
        abort_unless($staff->role === 'Parking Staff', 404);

        if ($request->user()->id === $staff->id) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        DB::transaction(function () use ($staff, $request) {
            $staffName = $staff->name;
            $staffEmail = $staff->email;

            AuditLogger::log(
                $request,
                'DELETE',
                'Deleted Parking Staff account: '
                    . $staffName . ' (' . $staffEmail . ')'
            );

            $staff->delete();
        });

        return redirect()
            ->route('staff.index')
            ->with('success', 'Parking Staff account deleted successfully.');
    }
}
