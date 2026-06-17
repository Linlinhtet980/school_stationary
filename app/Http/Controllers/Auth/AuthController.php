<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\pL\s\-]+$/u',
            'phone' => 'required|string|regex:/^09[0-9]{7,9}$/|unique:customers,phone',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $customerRole = Role::query()->where('name', 'Customer')->first();

        if (!$customerRole) {
            return back()->withInput()->withErrors(['role' => 'Customer role not found. Please run seeder.']);
        }

        $user = User::create([
            'role_id' => $customerRole->id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active'
        ]);

        Customer::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        Auth::login($user);
        
        return redirect()->route('home')->with('success', 'Registration successful!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Debug: Log login attempt
        \Log::info('Login attempt for email: ' . $request->email);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Debug: Log user info
            \Log::info('User authenticated: ' . $user->email . ', Status: ' . $user->status . ', Role ID: ' . $user->role_id);

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended.']);
            }

            if ($user->isCustomer()) {
                \Log::info('Redirecting customer to home page');
                return redirect()->route('home')->with('success', 'Login successful!');
            } else {
                \Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back!');
            }
        }

        // Debug: Log failed attempt
        \Log::info('Login failed for email: ' . $request->email);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
