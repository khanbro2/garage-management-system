<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Garage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GarageController extends Controller
{
    public function index()
    {
        $garages = Garage::with(['owner', 'currentSubscription.plan'])
            ->withCount(['customers', 'vehicles', 'users'])
            ->latest()
            ->paginate(10);

        return view('super-admin.garages.index', compact('garages'));
    }

    public function create()
    {
        return view('super-admin.garages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:garages,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            
            // Owner fields
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:8',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // Create garage (status defaults to 'active' in migration or add here)
                $garage = Garage::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'status' => 'active', // Your model uses 'status'
                    'password' => Hash::make(uniqid()), // Required in your fillable
                ]);

                // Create owner
                $owner = User::create([
                    'name' => $validated['owner_name'],
                    'email' => $validated['owner_email'],
                    'password' => Hash::make($validated['owner_password']),
                    'garage_id' => $garage->id,
                    'role' => 'garage_owner', // Matches your model relation
                ]);

                // Update garage with owner
                $garage->update(['owner_name' => $owner->name]); // Or create owner_id column
            });

            return redirect()
                ->route('super-admin.garages.index')
                ->with('success', 'Garage created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Garage $garage)
    {
        $garage->load(['owner', 'currentSubscription.plan']);
        
        $stats = [
            'customers' => $garage->customers()->count(),
            'vehicles' => $garage->vehicles()->count(),
            'staff' => $garage->users()->count(),
            'recent_mots' => 0, // Add if you have MOT records
        ];

        return view('super-admin.garages.show', compact('garage', 'stats'));
    }

    public function edit(Garage $garage)
    {
        $garage->load(['owner', 'currentSubscription.plan']);
        return view('super-admin.garages.edit', compact('garage'));
    }

    public function update(Request $request, Garage $garage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:garages,email,' . $garage->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $garage->update($validated);

        return redirect()
            ->route('super-admin.garages.index')
            ->with('success', 'Garage updated successfully.');
    }

    public function destroy(Garage $garage)
    {
        if ($garage->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete garage with active subscriptions.');
        }

        $garage->delete();

        return redirect()
            ->route('super-admin.garages.index')
            ->with('success', 'Garage deleted successfully.');
    }

    public function toggleStatus(Garage $garage)
    {
        $garage->update([
            'status' => $garage->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Garage status updated.');
    }

    public function impersonate(Garage $garage)
    {
        $owner = $garage->owner;
        
        if (!$owner) {
            return back()->with('error', 'No owner found for this garage.');
        }

        session(['impersonate_superadmin' => auth()->id()]);
        auth()->login($owner);

        return redirect()->route('dashboard')
            ->with('warning', 'Impersonating ' . $garage->name);
    }
}