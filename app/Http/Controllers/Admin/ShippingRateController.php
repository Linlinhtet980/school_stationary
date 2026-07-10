<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = \App\Models\ShippingRate::latest()->paginate(5);
        return view('admin.shipping_rates.index', compact('rates'));
    }

    public function create()
    {
        return view('admin.shipping_rates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_name' => 'required|string|unique:shipping_rates,region_name',
            'base_fee' => 'required|integer|min:0',
            'extra_fee_per_item' => 'required|integer|min:0',
        ]);

        \App\Models\ShippingRate::create($request->all());
        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate created successfully.');
    }

    public function edit(\App\Models\ShippingRate $shippingRate)
    {
        return view('admin.shipping_rates.edit', compact('shippingRate'));
    }

    public function update(Request $request, \App\Models\ShippingRate $shippingRate)
    {
        $request->validate([
            'region_name' => 'required|string|unique:shipping_rates,region_name,' . $shippingRate->id,
            'base_fee' => 'required|integer|min:0',
            'extra_fee_per_item' => 'required|integer|min:0',
        ]);

        $shippingRate->update($request->all());
        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate updated successfully.');
    }

    public function destroy(\App\Models\ShippingRate $shippingRate)
    {
        $shippingRate->delete();
        return redirect()->route('admin.shipping-rates.index')->with('success', 'Shipping rate deleted successfully.');
    }
}
