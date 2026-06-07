<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Type;
use App\Models\Brand;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['type', 'brand'])->latest()->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::where('status', 'active')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        return view('admin.items.create', compact('types', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_id'        => 'required|exists:types,id',
            'brand_id'       => 'nullable|exists:brands,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'         => 'required|in:active,inactive,out_of_stock',
        ]);

        $data = $request->except(['image', 'gallery_images']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('items/gallery', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['type', 'brand']);
        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $types = Type::where('status', 'active')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        return view('admin.items.edit', compact('item', 'types', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'type_id'        => 'required|exists:types,id',
            'brand_id'       => 'nullable|exists:brands,id',
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'         => 'required|in:active,inactive,out_of_stock',
        ]);

        $data = $request->except(['image', 'gallery_images']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        // Handle Gallery Images
        if ($request->hasFile('gallery_images')) {
            // Check current images count to enforce max 4 logic if desired, or just add
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('items/gallery', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        // Delete physical gallery images
        foreach ($item->images as $galleryImg) {
            if (Storage::disk('public')->exists($galleryImg->image_path)) {
                Storage::disk('public')->delete($galleryImg->image_path);
            }
        }

        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }

    /**
     * Remove the specified gallery image.
     */
    public function destroyImage($id)
    {
        $image = ItemImage::findOrFail($id);
        
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $image->delete();

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
}
