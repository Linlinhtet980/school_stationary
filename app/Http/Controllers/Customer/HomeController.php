<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Item;
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
                               ->take(8)
                               ->get();
            \Log::info('Featured items loaded: ' . $featuredItems->count());
        } catch (\Exception $e) {
            \Log::error('Featured items query error: ' . $e->getMessage());
            $featuredItems = collect();
        }

        return view('customer.home', compact('banners', 'featuredItems'));
    }
}
