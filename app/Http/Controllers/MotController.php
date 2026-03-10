<?php

namespace App\Http\Controllers;

use App\Services\MotApiService;
use App\Models\Vehicle;
use App\Models\Customer;
use Illuminate\Http\Request;

class MotController extends Controller
{
    protected $motService;

    public function __construct(MotApiService $motService)
    {
        $this->middleware(['auth', 'ensure.garage.access']);
        $this->motService = $motService;
    }

    public function index()
    {
        return view('mot.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'registration_number' => ['required', 'string', 'max:20'],
        ]);

        $registration = $this->motService->normalizeRegistration($request->registration_number);

        if (!$this->motService->validateRegistration($registration)) {
            return back()->with('error', 'Invalid registration number format.');
        }

        $motData = $this->motService->checkMotHistory($registration);

        if (!$motData) {
            return back()->with('error', 'No MOT data found for this vehicle.');
        }

        $existingVehicle = Vehicle::where('registration_number', $registration)->first();

        return view('mot.results', [
            'motData' => $motData,
            'registration' => $registration,
            'existingVehicle' => $existingVehicle,
            'customers' => Customer::all(),
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'registration_number' => ['required', 'string'],
            'customer_id' => ['required', 'exists:customers,id'],
            'make' => ['required', 'string'],
            'model' => ['required', 'string'],
            'year' => ['nullable', 'integer'],
            'mot_expiry' => ['nullable', 'date'],
        ]);

        $vehicle = Vehicle::where('registration_number', $request->registration_number)->first();

        if ($vehicle) {
            return redirect()->route('vehicles.show', $vehicle)
                ->with('info', 'Vehicle already exists in the system.');
        }

        $vehicle = Vehicle::create([
            'customer_id' => $request->customer_id,
            'registration_number' => $request->registration_number,
            'make' => $request->make,
            'model' => $request->model,
            'year' => $request->year,
            'mot_expiry' => $request->mot_expiry,
        ]);

        $motData = $this->motService->checkMotHistory($request->registration_number);
        if ($motData) {
            $this->motService->saveMotData($vehicle, $motData);
        }

        return redirect()->route('vehicles.show', $vehicle)
            ->with('success', 'Vehicle saved successfully with MOT history.');
    }
}