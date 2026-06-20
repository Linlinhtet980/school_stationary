<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Item;
use App\Models\Type;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Debug: Log home page access
        \Log::info('Home page accessed by user: ' . (Auth::check() ? Auth::user()->email : 'guest'));

        try {
            $banners = Banner::where('status', 'active')
                             ->orderBy('sequence', 'asc')
                             ->get();
            \Log::info('Banners loaded: ' . $banners->count());
        } catch (\Exception $e) {
            \Log::error('Banner query error: ' . $e->getMessage());
            $banners = collect();
        }

        try {
            $featuredItems = Item::where('status', 'active')
                               ->with(['variants', 'type'])
                               ->inRandomOrder()
                               ->take(12)
                               ->get();
            \Log::info('Featured items loaded: ' . $featuredItems->count());
        } catch (\Exception $e) {
            \Log::error('Featured items query error: ' . $e->getMessage());
            $featuredItems = collect();
        }

        try {
            $categories = Type::with('items')
                              ->whereHas('items', function($query) {
                                  $query->where('status', 'active');
                              })
                              ->take(12)
                              ->get();
            \Log::info('Categories loaded: ' . $categories->count());
        } catch (\Exception $e) {
            \Log::error('Categories query error: ' . $e->getMessage());
            $categories = collect();
        }

        try {
            // Bestsellers: Most sold items based on order count
            $bestsellers = Item::where('status', 'active')
                               ->with(['variants', 'type', 'orderItems'])
                               ->withCount('orderItems')
                               ->orderBy('order_items_count', 'desc')
                               ->take(12)
                               ->get();
            \Log::info('Bestsellers loaded: ' . $bestsellers->count());
        } catch (\Exception $e) {
            \Log::error('Bestsellers query error: ' . $e->getMessage());
            $bestsellers = collect();
        }

        try {
            // New Arrivals: Latest items
            $newArrivals = Item::where('status', 'active')
                              ->with(['variants', 'type'])
                              ->latest()
                              ->take(12)
                              ->get();
            \Log::info('New arrivals loaded: ' . $newArrivals->count());
        } catch (\Exception $e) {
            \Log::error('New arrivals query error: ' . $e->getMessage());
            $newArrivals = collect();
        }

        $brands = Brand::where('status', 'active')
                        ->orderBy('name', 'asc')
                        ->get();

        return view('customer.home', compact('banners', 'featuredItems', 'categories', 'bestsellers', 'newArrivals', 'brands'));
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        \Log::info('Contact form submission: ' . $validated['email']);

        // Here you would typically:
        // 1. Save to database
        // 2. Send email to admin
        // 3. Show success message

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
