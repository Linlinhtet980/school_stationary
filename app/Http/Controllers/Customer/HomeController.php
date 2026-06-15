<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner; 
use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
      public function index()
    {
        try {
            $banners = Banner::where('status', 'active')
                             ->orderBy('sequence', 'asc')
                             ->get();
                             
        } catch (\Exception $e) {
            $banners = collect();
        }

        return view('customer.home', compact('banners'));

        $banners = Banner::where('is_active', true)->get();
    }
}
