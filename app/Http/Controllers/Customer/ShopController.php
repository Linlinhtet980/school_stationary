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

        $query = $this->applyFilters($query, $request);

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
            $q->where('status', 'visible')->latest();
        }])->findOrFail($id);

        // Get related items from same category
        $relatedItems = Item::where('type_id', $item->type_id)
                           ->where('id', '!=', $item->id)
                           ->where('status', 'active')
                           ->with('variants')
                           ->take(10)
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
    public function newArrivals(Request $request)
    {
        $query = Item::with(['type', 'brand', 'variants'])
                    ->where('status', 'active');
        
        $query = $this->applyFilters($query, $request, 'latest');

        $items = $query->paginate(12)->appends($request->except('page'));

        $categories = Category::where('status', 'active')->with('types')->get();
        $brands = Brand::orderBy('name')->get();

        return view('customer.new_arrivals', compact('items', 'categories', 'brands'));
    }

    /**
     * Display bestsellers page
     */
    public function bestsellers(Request $request)
    {
        // Get items that have been ordered most frequently
        $query = Item::with(['type', 'brand', 'variants'])
                    ->where('status', 'active')
                    ->withCount(['orderItems as order_count']);
                    
        $query = $this->applyFilters($query, $request, 'bestselling');
        
        $items = $query->paginate(12)->appends($request->except('page'));

        $categories = Category::where('status', 'active')->with('types')->get();
        $brands = Brand::orderBy('name')->get();

        return view('customer.bestsellers', compact('items', 'categories', 'brands'));
    }

    /**
     * Display B2S deals page
     */
    public function b2sDeals()
    {
        $bundles = \App\Models\Bundle::with(['bundleItems.item.images', 'bundleItems.item.variants'])
                    ->where('status', 'active')
                    ->latest()
                    ->paginate(12);

        return view('customer.b2s_deals', compact('bundles'));
    }

    /**
     * Search products (AJAX endpoint for live autocomplete)
     */
    public function search(Request $request)
    {
        $search = trim($request->input('q', $request->input('search', '')));
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $searchTerms = [$search];
        if (stripos($search, 'stationary') !== false) {
            $searchTerms[] = str_ireplace('stationary', 'stationery', $search);
            $searchTerms[] = 'stationery';
        }

        $items = Item::where('status', 'active')
                    ->where(function($q) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            $q->orWhere('name', 'like', "%{$term}%")
                              ->orWhere('description', 'like', "%{$term}%")
                              ->orWhereHas('type', function($tQuery) use ($term) {
                                  $tQuery->where('name', 'like', "%{$term}%")
                                         ->orWhereHas('category', function($cQuery) use ($term) {
                                             $cQuery->where('name', 'like', "%{$term}%");
                                         });
                              })
                              ->orWhereHas('brand', function($bQuery) use ($term) {
                                  $bQuery->where('name', 'like', "%{$term}%");
                              });
                        }
                    })
                    ->with(['type', 'brand', 'variants'])
                    ->take(8)
                    ->get()
                    ->map(function($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'price' => $item->price_range,
                            'image' => $item->image ? asset('storage/' . $item->image) : null,
                            'url' => route('shop.show', $item->id)
                        ];
                    });

        return response()->json($items);
    }

    /**
     * Get first available variant for an item (AJAX endpoint)
     */
    public function getFirstVariant($id)
    {
        $item = Item::with('variants')->find($id);
        
        if (!$item || !$item->variants || $item->variants->isEmpty()) {
            return response()->json([
                'success' => false,
                'variant_id' => null
            ]);
        }

        // Get first variant with stock
        $firstVariant = $item->variants->first(function($variant) {
            return $variant->stock_quantity > 0;
        });

        if (!$firstVariant) {
            $firstVariant = $item->variants->first();
        }

        return response()->json([
            'success' => true,
            'variant_id' => $firstVariant->id,
            'item_name' => $item->name
        ]);
    }

    /**
     * Filter products via AJAX (for real-time filtering)
     */
    public function filter(Request $request)
    {
        $query = Item::with(['type', 'brand', 'variants'])->where('status', 'active');

        $query = $this->applyFilters($query, $request);

        $items = $query->paginate(12)->appends($request->except('page'));

        // Generate HTML for product grid
        $html = '';
        foreach ($items as $item) {
            $html .= '<div class="card">';
            
            // Image link
            $showRoute = route('shop.show', $item->id);
            $html .= '<a href="' . $showRoute . '" class="card-img-link">';
            if ($item->image) {
                $html .= '<img src="' . asset('storage/' . $item->image) . '" class="card-img" alt="' . htmlspecialchars($item->name) . '">';
            } else {
                $html .= '<div class="card-img no-image">No Image</div>';
            }
            $html .= '</a>';
            
            // Category/Type
            $categoryName = $item->type->name ?? 'Uncategorized';
            $html .= '<div class="card-category">' . htmlspecialchars($categoryName) . '</div>';
            
            // Title
            $html .= '<div class="card-title">' . htmlspecialchars($item->name) . '</div>';
            
            // Brand
            if ($item->brand) {
                $html .= '<div class="card-brand">' . htmlspecialchars($item->brand->name) . '</div>';
            }
            
            // Price
            $html .= '<div class="card-price-row">';
            if ($item->variants && $item->variants->count() > 0) {
                $minPrice = $item->variants->min('price');
                $maxPrice = $item->variants->max('price');
                $html .= '<div class="card-price">' . number_format($minPrice) . ' Ks</div>';
                if ($minPrice != $maxPrice) {
                    $html .= '<div class="card-price-range">- ' . number_format($maxPrice) . ' Ks</div>';
                }
            } else {
                $html .= '<div class="card-price">' . number_format($item->price) . ' Ks</div>';
            }
            $html .= '</div>';
            
            // Stock Status
            $totalStock = $item->variants ? $item->variants->sum('stock_quantity') : 0;
            if ($totalStock > 0) {
                $html .= '<div class="card-stock-status in-stock">';
                $html .= '<i class="fa-solid fa-check-circle"></i> In Stock';
                $html .= '</div>';
            } else {
                $html .= '<div class="card-stock-status out-of-stock">';
                $html .= '<i class="fa-solid fa-times-circle"></i> Out of Stock';
                $html .= '</div>';
            }
            
            // Add to Cart Button
            $html .= '<button class="btn-add" onclick="addToCart(' . $item->id . ')">';
            $html .= '<span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i>';
            $html .= '</button>';
            
            $html .= '</div>';
        }

        // Generate pagination HTML
        $pagination = $items->links('pagination.pagination')->toHtml();

        return response()->json([
            'success' => true,
            'html' => $html,
            'pagination' => $pagination,
            'total' => $items->total()
        ]);
    }

    /**
     * Apply common filters and sorting to product queries
     */
    private function applyFilters($query, Request $request, $defaultSort = 'latest')
    {
        // Search functionality
        if ($request->filled('search') || $request->filled('q')) {
            $search = trim($request->input('search', $request->input('q', '')));
            
            $searchTerms = [$search];
            if (stripos($search, 'stationary') !== false) {
                $searchTerms[] = str_ireplace('stationary', 'stationery', $search);
                $searchTerms[] = 'stationery';
            }

            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('name', 'like', "%{$term}%")
                      ->orWhere('description', 'like', "%{$term}%")
                      ->orWhereHas('type', function($tQuery) use ($term) {
                          $tQuery->where('name', 'like', "%{$term}%")
                                 ->orWhereHas('category', function($cQuery) use ($term) {
                                     $cQuery->where('name', 'like', "%{$term}%");
                                 });
                      })
                      ->orWhereHas('brand', function($bQuery) use ($term) {
                          $bQuery->where('name', 'like', "%{$term}%");
                      });
                }
            });
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
        $sort = $request->get('sort', $defaultSort);
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
            case 'bestselling':
                $query->orderBy('order_count', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        return $query;
    }

    /**
     * Display the About Us page
     */
    public function about()
    {
        return view('customer.about');
    }
}