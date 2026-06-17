<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Type;
use App\Models\Brand;
use App\Models\ItemVariant;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display shop page with filtering and search
     */
    public function index(Request $request)
    {
        $query = Item::with(['type', 'brand', 'variants'])->where('status', 'active');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Category filtering
        if ($request->filled('category')) {
            $categoryIds = explode(',', $request->category);
            $query->whereHas('type', function($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds);
            });
        }

        // Type filtering
        if ($request->filled('type')) {
            $typeIds = explode(',', $request->type);
            $query->whereIn('type_id', $typeIds);
        }

        // Brand filtering
        if ($request->filled('brand')) {
            $brandIds = explode(',', $request->brand);
            $query->whereIn('brand_id', $brandIds);
        }

        // Price range filtering
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        } elseif ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        } elseif ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock filtering
        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->whereHas('variants', function($q) {
                    $q->where('stock_quantity', '>', 0);
                });
            } elseif ($request->stock === 'out_of_stock') {
                $query->whereHas('variants', function($q) {
                    $q->where('stock_quantity', '<=', 0);
                });
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $items = $query->paginate(12)->appends($request->except('page'));

        // Get filter options
        $categories = Category::where('status', 'active')->with('types')->get();
        $brands = Brand::orderBy('name')->get();

        // Get price range
        $priceRange = Item::where('status', 'active')->selectRaw('MIN(price) as min, MAX(price) as max')->first();

        return view('customer.shop', compact('items', 'categories', 'brands', 'priceRange'));
    }

    /**
     * Display product detail page
     */
    public function show($id)
    {
        $item = Item::with(['type', 'brand', 'variants', 'images', 'reviews' => function($q) {
            $q->where('status', 'approved')->latest()->take(5);
        }])->findOrFail($id);

        // Get related items from same category
        $relatedItems = Item::where('type_id', $item->type_id)
                           ->where('id', '!=', $item->id)
                           ->where('status', 'active')
                           ->with('variants')
                           ->take(4)
                           ->get();

        // Calculate average rating
        $averageRating = $item->reviews->avg('rating') ?? 0;
        $reviewCount = $item->reviews->count();

        // Check if user is authenticated for cart functionality
        $isAuthenticated = auth()->check();

        return view('customer.product_detail', compact('item', 'relatedItems', 'averageRating', 'reviewCount', 'isAuthenticated'));
    }

    /**
     * Display new arrivals page
     */
    public function newArrivals()
    {
        $items = Item::with(['type', 'brand', 'variants'])
                    ->where('status', 'active')
                    ->latest()
                    ->take(12)
                    ->get();

        return view('customer.new_arrivals', compact('items'));
    }

    /**
     * Display bestsellers page
     */
    public function bestsellers()
    {
        // Get items that have been ordered most frequently
        $items = Item::with(['type', 'brand', 'variants'])
                    ->where('status', 'active')
                    ->withCount(['orderItems as order_count'])
                    ->orderBy('order_count', 'desc')
                    ->take(12)
                    ->get();

        return view('customer.bestsellers', compact('items'));
    }

    /**
     * Display B2S deals page
     */
    public function b2sDeals()
    {
        // Get items with discounted prices or special offers
        $items = Item::with(['type', 'brand', 'variants'])
                    ->where('status', 'active')
                    ->where(function($q) {
                        $q->where('price', '<', 10000) // Example logic for deals
                          ->orWhere('name', 'like', '%bundle%')
                          ->orWhere('name', 'like', '%pack%');
                    })
                    ->latest()
                    ->take(12)
                    ->get();

        return view('customer.b2s_deals', compact('items'));
    }

    /**
     * Search products (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $items = Item::where('status', 'active')
                    ->where('name', 'like', "%{$search}%")
                    ->with('variants')
                    ->take(10)
                    ->get();

        return response()->json($items);
    }
}