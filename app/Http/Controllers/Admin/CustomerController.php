<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('user')->oldest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->latest('id');
            } else {
                $query->oldest('id');
            }
        } else {
            $query->oldest();
        }

        $customers = $query->paginate(5)->appends($request->except('page'));
        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        // Load the customer's orders, addresses, wishlists and user data
        $customer->load([
            'user',
            'orders' => function($query) {
                $query->latest();
            },
            'addresses',
            'wishlists.item',
        ]);
        return view('admin.customers.show', compact('customer'));
    }

    public function block(Request $request, Customer $customer)
    {
        $user = $customer->user;
        
        if ($user) {
            $user->status = ($user->status === 'blocked') ? 'active' : 'blocked';
            $user->save();
            
            $message = ($user->status === 'blocked') ? 'Customer has been blocked successfully.' : 'Customer has been unblocked successfully.';
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'User account not found for this customer.');
    }
}
