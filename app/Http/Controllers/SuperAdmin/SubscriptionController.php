<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Garage;
use App\Models\GarageSubscription;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->middleware(['auth', 'role:super_admin']);
        $this->subscriptionService = $subscriptionService;
    }

    public function index()
    {
        $subscriptions = GarageSubscription::with(['garage', 'plan'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_active' => GarageSubscription::where('status', 'active')->where('ends_at', '>', now())->count(),
            'total_expired' => GarageSubscription::where('ends_at', '<', now())->count(),
            'expiring_soon' => GarageSubscription::where('status', 'active')
                ->where('ends_at', '<=', now()->addDays(7))
                ->where('ends_at', '>', now())
                ->count(),
        ];

        return view('super-admin.subscriptions.index', compact('subscriptions', 'stats'));
    }

    public function create()
    {
        $garages = Garage::whereDoesntHave('subscriptions', function ($q) {
            $q->where('status', 'active')->where('ends_at', '>', now());
        })->get();

        $plans = SubscriptionPlan::active()->get();

        return view('super-admin.subscriptions.create', compact('garages', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'garage_id' => 'required|exists:garages,id',
            'plan_slug' => 'required|exists:subscription_plans,slug',
            'billing_cycle' => 'required|in:monthly,yearly',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        $garage = Garage::findOrFail($request->garage_id);
        
        // Cancel any existing active subscriptions
        $garage->subscriptions()
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $plan = SubscriptionPlan::where('slug', $request->plan_slug)->firstOrFail();

        GarageSubscription::create([
            'garage_id' => $request->garage_id,
            'subscription_plan_id' => $plan->id,
            'billing_cycle' => $request->billing_cycle,
            'status' => 'active',
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        // Activate the garage
        $garage->update(['status' => 'active']);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    public function edit(GarageSubscription $subscription)
    {
        $plans = SubscriptionPlan::active()->get();
        return view('super-admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(Request $request, GarageSubscription $subscription)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:active,cancelled,expired,pending',
            'ends_at' => 'required|date',
        ]);

        $subscription->update([
            'subscription_plan_id' => $request->subscription_plan_id,
            'billing_cycle' => $request->billing_cycle,
            'status' => $request->status,
            'ends_at' => $request->ends_at,
            'cancelled_at' => $request->status === 'cancelled' ? now() : $subscription->cancelled_at,
        ]);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    public function destroy(GarageSubscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    public function renew(GarageSubscription $subscription)
    {
        $months = $subscription->billing_cycle === 'yearly' ? 12 : 1;
        $subscription->renew($months);

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription renewed successfully.');
    }

    public function cancel(GarageSubscription $subscription)
    {
        $subscription->cancel();

        return redirect()->route('superadmin.subscriptions.index')
            ->with('success', 'Subscription cancelled successfully.');
    }
}