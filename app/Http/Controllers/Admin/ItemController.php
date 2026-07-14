<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Type;
use App\Models\Brand;
use App\Models\ItemImage;
use App\Models\ItemVariant; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ဒီစာကြောင်းလေးကို အရင်ဆုံး ထည့်ပေးပါ
        $query = Item::query(); 

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('stock') && $request->stock !== 'all') {
            if ($request->stock === 'in_stock') {
                $query->where('stock_quantity', '>', 5);
            } elseif ($request->stock === 'low_stock') {
                $query->whereBetween('stock_quantity', [1, 5]);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            }
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

        $items = $query->paginate(5)->appends($request->except('page'));
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
        // အရိုးရှင်းဆုံး Validation စစ်ဆေးခြင်း
        $request->validate([
            'type_id' => 'required|exists:types,id',
            'name'    => 'required|string|min:3|max:100',
            'status'  => 'required',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif,gif,svg,bmp,avif|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif,gif,svg,bmp,avif|max:10240',
            'variants' => 'nullable|array',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_qty' => 'nullable|integer|min:0',
        ]);

        $uploadedImages = [];

        DB::beginTransaction();

        try {
            $data = $request->except(['image', 'gallery_images', 'variants']);

            // ၁။ Main Image အပ်လုဒ်တင်ခြင်း
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('items', 'public');
                $data['image'] = $path;
                $uploadedImages[] = $path; // မှတ်သားထားမည်
            }

            // ၂။ Item အဓိက အချက်အလက် သိမ်းဆည်းခြင်း
            $item = Item::create($data);

            // ၃။ Variants များ သိမ်းဆည်းခြင်း (လွတ်နေရင် Default တန်ဖိုးတွေ အစားထိုးထည့်ပေးမည်)
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    $item->variants()->create([
                        'unit_label' => $variantData['unit_label'] ?? 'Default',
                        'unit_qty'   => $variantData['unit_qty'] ?: 1,
                        'color'      => $variantData['color'] ?: null,
                        'size'       => $variantData['size'] ?: null,
                        'price'      => $variantData['price'] ?: 0,
                        'stock_quantity'  => $variantData['stock_qty'] ?: 0,
                        'sku'        => $variantData['sku'] ?: null,
                    ]);
                }
            }

            // ၄။ Gallery Images များ သိမ်းဆည်းခြင်း
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $path = $file->store('items/gallery', 'public');
                    $uploadedImages[] = $path; // မှတ်သားထားမည်

                    ItemImage::create([
                        'item_id' => $item->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // အားလုံး အောင်မြင်ပါက DB ထဲသို့ အပြီးသတ် သိမ်းဆည်းမည်
            DB::commit();

            return redirect()->route('admin.items.index')->with('success', 'Item and variants created successfully.');

        } catch (\Exception $e) {
            // တစ်ဆင့်ဆင့်တွင် Error တက်ပါက DB ကို မူလအတိုင်း ပြန်ပြင်မည်
            DB::rollBack();

            // တင်မိသွားသော ပုံများကိုပါ Storage ထဲမှ ပြန်ဖျက်ပေးမည် (Orphaned Files မကျန်စေရန်)
            foreach ($uploadedImages as $imgPath) {
                if (Storage::disk('public')->exists($imgPath)) {
                    Storage::disk('public')->delete($imgPath);
                }
            }

            Log::error('Item Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
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
        $item->load(['variants', 'images']); 
        return view('admin.items.edit', compact('item', 'types', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        // အရိုးရှင်းဆုံး Validation စစ်ဆေးခြင်း
        $request->validate([
            'type_id' => 'required|exists:types,id',
            'name'    => 'required|string|min:3|max:100',
            'status'  => 'required',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif,gif,svg,bmp,avif|max:10240',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif,gif,svg,bmp,avif|max:10240',
            'variants' => 'nullable|array',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock_qty' => 'nullable|integer|min:0',
        ]);
        DB::beginTransaction();

        try {
            $oldTotalStock = $item->total_stock;
            $data = $request->except(['image', 'gallery_images', 'variants', '_method', '_token']);
            
            // အဆင့် (၁) - ပုံအသစ်ပါလာရင် ပုံဟောင်းကိုဖျက်ပြီး ပုံသစ်ကို သိမ်းပါမယ်
            if ($request->hasFile('image')) {
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }
                $data['image'] = $request->file('image')->store('items', 'public');
            }

            // အဆင့် (၂) - Item ရဲ့ အခြေခံအချက်အလက် (နာမည်၊ ဈေး၊ စသည်) ကို Update လုပ်ပါမယ်
            $item->update($data);

            // အဆင့် (၃) - Item Variants တွေကို Update လုပ်ပါမယ်
            // - Variant ID ပါလာသော (existing) variant: stock ကို ထပ်ပေါင်း (accumulate) ဆောင်ရွက်မည်
            // - Variant ID မပါသော (new) variant: အသစ်ဖန်တီးပြီး stock_qty ကို direct သိမ်းမည်
            if ($request->has('variants')) {
                // Track which variant IDs are still in the form (to delete removed ones)
                $submittedVariantIds = [];

                foreach ($request->variants as $variantData) {
                    $variantId = $variantData['variant_id'] ?? null;

                    if ($variantId) {
                        // Existing variant: update fields, accumulate stock
                        $existingVariant = $item->variants()->find($variantId);
                        if ($existingVariant) {
                            $addStock = intval($variantData['add_stock'] ?? 0);
                            $existingVariant->update([
                                'unit_label'     => $variantData['unit_label'] ?? 'Default',
                                'unit_qty'       => $variantData['unit_qty'] ?: 1,
                                'color'          => $variantData['color'] ?: null,
                                'size'           => $variantData['size'] ?: null,
                                'price'          => $variantData['price'] ?: 0,
                                'sku'            => $variantData['sku'] ?: null,
                                'stock_quantity' => $existingVariant->stock_quantity + $addStock,
                            ]);
                            $submittedVariantIds[] = $existingVariant->id;
                        }
                    } else {
                        // New variant row: create directly with entered stock_qty
                        $newVariant = $item->variants()->create([
                            'unit_label'     => $variantData['unit_label'] ?? 'Default',
                            'unit_qty'       => $variantData['unit_qty'] ?: 1,
                            'color'          => $variantData['color'] ?: null,
                            'size'           => $variantData['size'] ?: null,
                            'price'          => $variantData['price'] ?: 0,
                            'stock_quantity' => $variantData['stock_qty'] ?: 0,
                            'sku'            => $variantData['sku'] ?: null,
                        ]);
                        $submittedVariantIds[] = $newVariant->id;
                    }
                }

                // Delete variants that were removed from the form
                $item->variants()->whereNotIn('id', $submittedVariantIds)->delete();
            }

            // Reload variants to calculate new total stock accurately
            $item->load('variants');
            $newTotalStock = $item->total_stock;

            // Trigger Notifications
            if ($oldTotalStock == 0 && $newTotalStock > 0) {
                $wishlists = \App\Models\Wishlist::where('item_id', $item->id)->with('user')->get();
                foreach ($wishlists as $wishlist) {
                    if ($wishlist->user) {
                        $wishlist->user->notify(new \App\Notifications\WishlistBackInStock($item));
                    }
                }
            } elseif ($oldTotalStock > 5 && $newTotalStock > 0 && $newTotalStock <= 5) {
                $wishlists = \App\Models\Wishlist::where('item_id', $item->id)->with('user')->get();
                foreach ($wishlists as $wishlist) {
                    if ($wishlist->user) {
                        $wishlist->user->notify(new \App\Notifications\WishlistLowStock($item));
                    }
                }
            }

            // အဆင့် (၄) - ပုံအပို (Gallery Images) အသစ်တွေ ထပ်ပါလာရင် သိမ်းပါမယ်
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $file) {
                    $path = $file->store('items/gallery', 'public');
                    ItemImage::create([
                        'item_id' => $item->id,
                        'image_path' => $path,
                    ]);
                }
            }

            // အဆင့်အားလုံး အောင်မြင်မှ Database ထဲ အတည်ပြု သိမ်းဆည်းပါမယ် (Commit)
            DB::commit();

            return redirect()->route('admin.items.index')->with('success', 'Item and variants updated successfully.');

        } catch (\Exception $e) {
            // တစ်ခုခု မှားယွင်းသွားရင် သိမ်းထားသမျှ အကုန်လုံးကို နောက်ပြန်ဆုတ်ပါမယ် (Rollback)
            DB::rollBack();
            Log::error('Item Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        DB::beginTransaction();

        try {
            $mainImage = $item->image;
            $galleryImages = $item->images; 

            $item->delete(); 

            DB::commit();

            if ($mainImage && Storage::disk('public')->exists($mainImage)) {
                Storage::disk('public')->delete($mainImage);
            }

            foreach ($galleryImages as $galleryImg) {
                if (Storage::disk('public')->exists($galleryImg->image_path)) {
                    Storage::disk('public')->delete($galleryImg->image_path);
                }
            }

            return redirect()->route('admin.items.index')->with('success', 'Item and associated files deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Item Delete Error: ' . $e->getMessage());
            return redirect()->route('admin.items.index')->with('error', 'Delete failed.');
        }
    }


    /**
     * Remove the specified gallery image.
     */
    public function destroyImage(int $id)
    {
        try {
            $image = ItemImage::findOrFail($id);
            $imagePath = $image->image_path;
            $image->delete(); // Delete from database

            // Physical file ဖျက်ခြင်း
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Image removed successfully.']);
            }

            return redirect()->back()->with('success', 'Image removed successfully.');
        } catch (\Exception $e) {
            Log::error('Gallery Image Delete Error: ' . $e->getMessage());
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to remove image.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to remove image.');
        }
    }
}