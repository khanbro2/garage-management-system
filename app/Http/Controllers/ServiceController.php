<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Models\Vehicle;
use App\Http\Requests\StoreServiceRequest;
use App\Services\ReminderService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $reminderService;

    public function __construct(ReminderService $reminderService)
    {
        $this->middleware(['auth', 'ensure.garage.access']);
        $this->reminderService = $reminderService;
    }

    public function index(Request $request)
    {
        $query = ServiceRecord::with(['vehicle.customer', 'user']);

        if ($request->has('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        $services = $query->latest()->paginate(20);

        return view('services.index', compact('services'));
    }

    public function create(Request $request)
    {
        $vehicles = Vehicle::with('customer')->get();
        $preselectedVehicle = $request->get('vehicle_id');

        return view('services.create', compact('vehicles', 'preselectedVehicle'));
    }

    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $service = ServiceRecord::create($data);

        if ($request->has('next_service_due')) {
            $vehicle = Vehicle::find($request->vehicle_id);
            $vehicle->update(['service_due' => $request->next_service_due]);
            $this->reminderService->rescheduleReminders($vehicle);
        }

        return redirect()->route('services.show', $service)
            ->with('success', 'Service record added successfully.');
    }

    public function show(ServiceRecord $service)
    {
        $service->load(['vehicle.customer', 'user']);
        return view('services.show', compact('service'));
    }

    public function edit(ServiceRecord $service)
    {
        $vehicles = Vehicle::with('customer')->get();
        return view('services.edit', compact('service', 'vehicles'));
    }

    public function update(StoreServiceRequest $request, ServiceRecord $service)
    {
        $service->update($request->validated());

        return redirect()->route('services.show', $service)
            ->with('success', 'Service record updated successfully.');
    }

    public function destroy(ServiceRecord $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service record deleted successfully.');
    }
}