<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        $staffUsers = User::whereHas('role', function($query){
            $query->where('name', '!=', 'Customer');
        })->with(['staff', 'role'])->get();

        return view('admin.staff.index', compact('staffUsers'));
    }

    public function create()
    {
        $roles = Role::query()->where('name', '!=', 'Customer')->get();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive'
        ]);

        // ၁။ User Table တွင် အကောင့်အရင် ဖန်တီးသည်
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        // ၂။ Staff Table တွင် ကိုယ်ရေးအချက်အလက် သိမ်းသည်
        Staff::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created successfully.');
    }

    public function edit(User $staff)
    {
        $roles = Role::query()->where('name', '!=', 'Customer')->get();
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($staff->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8', // Password မပြောင်းလိုပါက အလွတ်ထားနိုင်သည်
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive'
        ]);

        // User Data Update
        $staff->update([
            'email' => $request->email,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        // Password အသစ်ရိုက်ထည့်ထားပါက Update လုပ်မည်
        if ($request->filled('password')) {
            $staff->update(['password' => Hash::make($request->password)]);
        }

        // Staff Profile Update လုပ်မည် (မရှိသေးပါက အသစ်ဖန်တီးမည်)
        Staff::updateOrCreate(
            ['user_id' => $staff->id],
            ['name' => $request->name, 'phone' => $request->phone]
        );

        return redirect()->route('admin.staff.index')->with('success', 'Staff account updated successfully.');
    }

    public function destroy(User $staff)
    {
        // မိမိကိုယ်တိုင်၏ အကောင့်ကို ဖျက်ခွင့်မပြုပါ
        if (Auth::id() === $staff->id) {
            return redirect()->route('admin.staff.index')->withErrors('You cannot delete your own account.');
        }
        
        User::destroy($staff->id); 
        
        return redirect()->route('admin.staff.index')->with('success', 'Staff account deleted successfully.');
    }

}
