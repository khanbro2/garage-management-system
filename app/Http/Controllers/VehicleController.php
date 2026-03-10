<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Http\Requests\StoreVehicleRequest;
use App\Services\MotApiService;
use App\Services\ReminderService;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    protected $motService;
    protected $reminderService;

    public function __construct(MotApiService $motService, ReminderService $reminderService)
    {
        $this->middleware(['auth', 'ensure.garage.access']);
        $this->motService = $motService;
        $this->reminderService = $reminderService;
    }

    public function index(Request $request)
    {
        $query = Vehicle::with('customer');

        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('status')) {
            if ($request->status === 'mot_expiring') {
                $query->motExpiringSoon(30);
            } elseif ($request->status === 'service_due') {
                $query->serviceDueSoon(30);
            }
        }

        $vehicles = $query->latest()->paginate(20);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create(Request $request)
    {
        $customers = Customer::all();
        $preselectedCustomer = $request->get('customer_id');
        
        return view('vehicles.create', compact('customers', 'preselectedCustomer'));
    }

    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        $this->reminderService->createRemindersForVehicle($vehicle);

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle added successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['customer', 'motRecords', 'serviceRecords.user']);
        
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $customers = Customer::all();
        return view('vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(StoreVehicleRequest $request, Vehicle $vehicle)
    {
        $oldValues = $vehicle->toArray();
        $vehicle->update($request->validated());

        if ($oldValues['mot_expiry'] != $vehicle->mot_expiry || 
            $oldValues['service_due'] != $vehicle->service_due) {
            $this->reminderService->rescheduleReminders($vehicle);
        }

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->reminderService->cancelVehicleReminders($vehicle);
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    public function checkMot(Vehicle $vehicle)
    {
        $motData = $this->motService->checkMotHistory($vehicle->registration_number);

        if ($motData) {
            $this->motService->saveMotData($vehicle, $motData);
            $this->reminderService->rescheduleReminders($vehicle);

            return back()->with('success', 'MOT history updated successfully.');
        }

        return back()->with('error', 'Could not fetch MOT data. Please try again.');
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        
        $vehicles = Vehicle::search($term)
            ->with('customer')
            ->limit(10)
            ->get();

        return response()->json($vehicles);
    }
}