<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $garage = \App\Models\Garage::where('email', $request->email)->first();
        
        if ($garage && Auth::guard('garage')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $owner = $garage->owner;
            Auth::login($owner);
            $this->tenantManager->setGarage($garage);
            
            return redirect()->intended(route('dashboard'));
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->garage_id) {
                $this->tenantManager->setGarage($user->garage);
            }

            if ($user->isSuperAdmin()) {
                return redirect()->intended(route('superadmin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Auth::guard('garage')->logout();
        
        $this->tenantManager->clearGarage();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}