<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('super-admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('super-admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_vehicles' => 'nullable|integer|min:1',
            'max_staff' => 'nullable|integer|min:1',
            'sms_reminders' => 'boolean',
            'api_access' => 'boolean',
            'advanced_reporting' => 'boolean',
            'multiple_locations' => 'boolean',
        ]);

        SubscriptionPlan::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price_monthly' => $request->price_monthly,
            'price_yearly' => $request->price_yearly,
            'max_vehicles' => $request->max_vehicles,
            'max_staff' => $request->max_staff,
            'sms_reminders' => $request->boolean('sms_reminders'),
            'api_access' => $request->boolean('api_access'),
            'advanced_reporting' => $request->boolean('advanced_reporting'),
            'multiple_locations' => $request->boolean('multiple_locations'),
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('super-admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_vehicles' => 'nullable|integer|min:1',
            'max_staff' => 'nullable|integer|min:1',
            'sms_reminders' => 'boolean',
            'api_access' => 'boolean',
            'advanced_reporting' => 'boolean',
            'multiple_locations' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'price_monthly' => $request->price_monthly,
            'price_yearly' => $request->price_yearly,
            'max_vehicles' => $request->max_vehicles,
            'max_staff' => $request->max_staff,
            'sms_reminders' => $request->boolean('sms_reminders'),
            'api_access' => $request->boolean('api_access'),
            'advanced_reporting' => $request->boolean('advanced_reporting'),
            'multiple_locations' => $request->boolean('multiple_locations'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        // Check if plan has active subscriptions
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}