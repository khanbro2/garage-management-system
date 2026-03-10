<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\ServiceRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.garage.access']);
    }

    public function index()
    {
        $user = auth()->user();
        
        // Redirect Super Admin to admin dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        
        $garage = $user->garage;
        
        // If no garage, show error
        if (!$garage) {
            return redirect()->route('subscription.required')
                ->with('error', 'No garage assigned to your account.');
        }
        
        $stats = [
            'total_customers' => $garage->customers()->count(),
            'total_vehicles' => $garage->vehicles()->count(),
            'total_services_this_month' => ServiceRecord::whereHas('vehicle', function ($q) use ($garage) {
                $q->where('garage_id', $garage->id);
            })->whereMonth('service_date', Carbon::now()->month)
              ->whereYear('service_date', Carbon::now()->year)
              ->count(),
        ];

        $motExpiringSoon = $garage->vehicles()
            ->with('customer')
            ->motExpiringSoon(30)
            ->orderBy('mot_expiry')
            ->limit(10)
            ->get();

        $serviceDueSoon = $garage->vehicles()
            ->with('customer')
            ->serviceDueSoon(30)
            ->orderBy('service_due')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('stats', 'motExpiringSoon', 'serviceDueSoon'));
    }
}