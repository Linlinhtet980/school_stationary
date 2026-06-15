<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        $query = Bundle::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'oldest') {
                $query->oldest('id');
            } else {
                $query->latest('id');
            }
        } else {
            $query->latest('id');
        }

        $bundles = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.bundles.partials.table', compact('bundles'))->render();
        }

        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        $items = Item::where('status', 'active')->get();
        return view('admin.bundles.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bundle_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $data = $request->except(['image', 'items']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('bundles', 'public');
        }

        $bundle = Bundle::create($data);

        foreach ($request->items as $itemData) {
            BundleItem::create([
                'bundle_id' => $bundle->id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
            ]);
        }

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle created successfully.');
    }

    public function edit(Bundle $bundle)
    {
        $items = Item::where('status', 'active')->get();
        $bundle->load('bundleItems.item');
        return view('admin.bundles.edit', compact('bundle', 'items'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bundle_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $data = $request->except(['image', 'items']);

        if ($request->hasFile('image')) {
            if ($bundle->image) {
                Storage::disk('public')->delete($bundle->image);
            }
            $data['image'] = $request->file('image')->store('bundles', 'public');
        }

        $bundle->update($data);

        // Update items: delete old ones and recreate
        $bundle->bundleItems()->delete();

        foreach ($request->items as $itemData) {
            BundleItem::create([
                'bundle_id' => $bundle->id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
            ]);
        }

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle updated successfully.');
    }

    public function destroy(Bundle $bundle)
    {
        if ($bundle->image) {
            Storage::disk('public')->delete($bundle->image);
        }
        $bundle->delete();

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle deleted successfully.');
    }
}
