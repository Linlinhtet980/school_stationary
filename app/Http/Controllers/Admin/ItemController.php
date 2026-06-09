<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Type;
use App\Models\Brand;
use App\Models\ItemImage;
use App\Models\ItemVariant; // Variant Model ကို ထည့်သွင်းထားပါသည်
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Variant များကိုပါ ကြိုတင်ဆွဲထုတ်ထားရန် with() တွင် 'variants' ကို ထည့်ထားပါသည်
        $items = Item::with(['type', 'brand', 'variants'])->latest()->paginate(5);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::where(['status' => 'active'])->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        return view('admin.items.create', compact('types', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type_id'          => 'required|exists:types,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            // Main Item ၏ စျေးနှင့် စတော့ခ်ကို Variant ဘက်ရွှေ့မည်ဖြစ်၍ nullable ပေးထားပါသည်
            'price'            => 'nullable|numeric|min:0',
            'stock_quantity'   => 'nullable|integer|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'           => 'required|in:active,inactive,out_of_stock',
            
            // Variants အတွက် Validation များ
            'variants'             => 'required|array|min:1',
            'variants.*.price'     => 'required|numeric|min:0',
            'variants.*.stock_qty' => 'required|integer|min:0',
        ]);

        $data = $request->except(['image', 'gallery_images', 'variants']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        // Handle Variants သိမ်းဆည်းခြင်း
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $item->variants()->create([
                    'unit_label' => $variantData['unit_label'] ?? null,
                    'unit_qty'   => $variantData['unit_qty'] ?? null,
                    'color'      => $variantData['color'] ?? null,
                    'size'       => $variantData['size'] ?? null,
                    'price'      => $variantData['price'],
                    'stock_qty'  => $variantData['stock_qty'],
                    'sku'        => $variantData['sku'] ?? null,
                ]);
            }
        }

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

        return redirect()->route('admin.items.index')->with('success', 'Item and variants created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        // View တွင် ပြသရန် variants နှင့် images များကိုပါ load လုပ်ပါသည်
        $item->load(['type', 'brand', 'variants', 'images']);
        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $types = Type::where('status', 'active')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        // Edit Form တွင် Variant အဟောင်းများကို ပြန်ပြရန် load လုပ်ပါသည်
        $item->load(['variants', 'images']); 
        return view('admin.items.edit', compact('item', 'types', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'type_id'          => 'required|exists:types,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'nullable|numeric|min:0',
            'stock_quantity'   => 'nullable|integer|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'           => 'required|in:active,inactive,out_of_stock',
            
            // Variants အတွက် Validation များ
            'variants'             => 'required|array|min:1',
            'variants.*.price'     => 'required|numeric|min:0',
            'variants.*.stock_qty' => 'required|integer|min:0',
        ]);

        $data = $request->except(['image', 'gallery_images', 'variants']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        // Handle Variants ပြင်ဆင်ခြင်း (အဟောင်းများကိုဖျက်၍ အသစ်များ အစားထိုးခြင်း)
        if ($request->has('variants')) {
            $item->variants()->delete(); 
            
            foreach ($request->variants as $variantData) {
                $item->variants()->create([
                    'unit_label' => $variantData['unit_label'] ?? null,
                    'unit_qty'   => $variantData['unit_qty'] ?? null,
                    'color'      => $variantData['color'] ?? null,
                    'size'       => $variantData['size'] ?? null,
                    'price'      => $variantData['price'],
                    'stock_qty'  => $variantData['stock_qty'],
                    'sku'        => $variantData['sku'] ?? null,
                ]);
            }
        }

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

        return redirect()->route('admin.items.index')->with('success', 'Item and variants updated successfully.');
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

        Item::destroy($item->id); // Database မှ ဖျက်သည့်အခါ Cascade ဖြစ်၍ variants များပါ အလိုအလျောက် ပျက်သွားပါမည်

        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }

    /**
     * Remove the specified gallery image.
     */
    public function destroyImage(int $id)
    {
        $image = ItemImage::findOrFail($id);
        
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $image->delete();

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
}