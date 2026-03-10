<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreStaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.garage.access', 'role:garage_owner,super_admin']);
    }

    public function index()
    {
        $staff = User::where('garage_id', auth()->user()->garage_id)
            ->where('role', 'garage_staff')
            ->paginate(20);

        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(StoreStaffRequest $request)
    {
        $garage = auth()->user()->garage;

        if (!$garage->canAddStaff()) {
            return back()->with('error', 'You have reached the maximum number of staff for your plan.');
        }

        User::create([
            'garage_id' => $garage->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'garage_staff',
            'is_active' => true,
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member added successfully.');
    }

    public function edit(User $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $staff->id],
            'is_active' => ['boolean'],
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->filled('password')) {
            $staff->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff member removed successfully.');
    }
}