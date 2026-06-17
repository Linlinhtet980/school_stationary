@extends('layouts.customer')

@section('title', 'Shop - Campus Supply')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/customer/shop.css') }}">
@endpush

@section('content')
<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">SHOP ALL PRODUCTS</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / Shop
        </div>
    </div>

    <!-- Shop Container -->
    <div class="shop-container">
        
        <!-- LEFT: Sidebar Filters -->
        <aside class="sidebar">
            <!-- Search -->
            <div class="filter-group">
                <h3 class="filter-title">Search</h3>
                <div class="search-box">
                    <input type="text" 
                           id="searchInput" 
                           class="search-input" 
                           placeholder="Search products..." 
                           value="{{ request('search') }}">
                    <button class="search-btn" onclick="applyFilters()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="filter-group">
                <h3 class="filter-title">Categories</h3>
                <ul class="filter-list">
                    <li>
                        <label class="custom-checkbox">
                            <input type="checkbox" 
                                   class="filter-checkbox" 
                                   value="all" 
                                   {{ request('category') ? '' : 'checked' }}
                                   onchange="handleCategoryChange(this)">
                            All Categories
                        </label>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <label class="custom-checkbox">
                                <input type="checkbox" 
                                       class="filter-checkbox category-checkbox" 
                                       value="{{ $category->id }}" 
                                       {{ in_array($category->id, explode(',', request('category') ?? '')) ? 'checked' : '' }}
                                       onchange="handleCategoryChange(this)">
                                {{ $category->name }}
                            </label>
                            @if($category->types && $category->types->count() > 0)
                                <ul class="sub-filter-list">
                                    @foreach($category->types as $type)
                                        <li>
                                            <label class="custom-checkbox">
                                                <input type="checkbox" 
                                                       class="filter-checkbox type-checkbox" 
                                                       value="{{ $type->id }}" 
                                                       data-category="{{ $category->id }}"
                                                       {{ in_array($type->id, explode(',', request('type') ?? '')) ? 'checked' : '' }}
                                                       onchange="applyFilters()">
                                                {{ $type->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Price Filter -->
            <div class="filter-group">
                <h3 class="filter-title">Price Range</h3>
                <div class="price-range-wrapper">
                    <input type="number" 
                           id="minPrice" 
                           class="price-input" 
                           placeholder="Min" 
                           value="{{ request('min_price') }}">
                    <span>-</span>
                    <input type="number" 
                           id="maxPrice" 
                           class="price-input" 
                           placeholder="Max" 
                           value="{{ request('max_price') }}">
                </div>
                <button class="apply-price-btn" onclick="applyFilters()">Apply</button>
            </div>

            <!-- Brand Filter -->
            <div class="filter-group">
                <h3 class="filter-title">Brands</h3>
                <ul class="filter-list">
                    @foreach($brands as $brand)
                        <li>
                            <label class="custom-checkbox">
                                <input type="checkbox" 
                                       class="filter-checkbox brand-checkbox" 
                                       value="{{ $brand->id }}" 
                                       {{ in_array($brand->id, explode(',', request('brand') ?? '')) ? 'checked' : '' }}
                                       onchange="applyFilters()">
                                {{ $brand->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Stock Filter -->
            <div class="filter-group">
                <h3 class="filter-title">Stock Status</h3>
                <ul class="filter-list">
                    <li>
                        <label class="custom-radio">
                            <input type="radio" 
                                   name="stock" 
                                   value="" 
                                   {{ !request('stock') ? 'checked' : '' }}
                                   onchange="applyFilters()">
                            All
                        </label>
                    </li>
                    <li>
                        <label class="custom-radio">
                            <input type="radio" 
                                   name="stock" 
                                   value="in_stock" 
                                   {{ request('stock') == 'in_stock' ? 'checked' : '' }}
                                   onchange="applyFilters()">
                            In Stock
                        </label>
                    </li>
                    <li>
                        <label class="custom-radio">
                            <input type="radio" 
                                   name="stock" 
                                   value="out_of_stock" 
                                   {{ request('stock') == 'out_of_stock' ? 'checked' : '' }}
                                   onchange="applyFilters()">
                            Out of Stock
                        </label>
                    </li>
                </ul>
            </div>

            <!-- Clear Filters -->
            <button class="clear-filters-btn" onclick="clearFilters()">
                <i class="fa-solid fa-times"></i> Clear All Filters
            </button>
        </aside>

        <!-- RIGHT: Main Product Area -->
        <div class="shop-main">
            
            <!-- Topbar (Sort & Results) -->
            <div class="shop-topbar">
                <div class="results-count">
                    Showing {{ $items->firstItem() }}-{{ $items->lastItem() }} of {{ $items->total() }} results
                </div>
                <div class="sort-wrapper">
                    <select class="sort-select" id="sortSelect" onchange="applyFilters()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if(request()->except('page'))
                <div class="active-filters">
                    <span class="active-filters-label">Active Filters:</span>
                    @if(request('search'))
                        <span class="active-filter-tag">
                            Search: "{{ request('search') }}"
                            <button onclick="removeFilter('search')"><i class="fa-solid fa-times"></i></button>
                        </span>
                    @endif
                    @if(request('category'))
                        @foreach(explode(',', request('category')) as $catId)
                            @if($category = $categories->find($catId))
                                <span class="active-filter-tag">
                                    {{ $category->name }}
                                    <button onclick="removeFilterValue('category', {{ $catId }})"><i class="fa-solid fa-times"></i></button>
                                </span>
                            @endif
                        @endforeach
                    @endif
                    @if(request('type'))
                        @foreach(explode(',', request('type')) as $typeId)
                            @if($type = \App\Models\Type::find($typeId))
                                <span class="active-filter-tag">
                                    {{ $type->name }}
                                    <button onclick="removeFilterValue('type', {{ $typeId }})"><i class="fa-solid fa-times"></i></button>
                                </span>
                            @endif
                        @endforeach
                    @endif
                    @if(request('brand'))
                        @foreach(explode(',', request('brand')) as $brandId)
                            @if($brand = $brands->find($brandId))
                                <span class="active-filter-tag">
                                    {{ $brand->name }}
                                    <button onclick="removeFilterValue('brand', {{ $brandId }})"><i class="fa-solid fa-times"></i></button>
                                </span>
                            @endif
                        @endforeach
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <span class="active-filter-tag">
                            Price: {{ request('min_price') ?? '0' }} - {{ request('max_price') ?? '∞' }}
                            <button onclick="removeFilter('min_price'); removeFilter('max_price');"><i class="fa-solid fa-times"></i></button>
                        </span>
                    @endif
                    @if(request('stock'))
                        <span class="active-filter-tag">
                            {{ request('stock') == 'in_stock' ? 'In Stock' : 'Out of Stock' }}
                            <button onclick="removeFilter('stock')"><i class="fa-solid fa-times"></i></button>
                        </span>
                    @endif
                </div>
            @endif

            <!-- Product Grid -->
            <div class="shop-grid">
                @forelse($items as $item)
                    <div class="card">
                        <a href="{{ route('shop.show', $item->id) }}" class="card-img-link">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img" alt="{{ $item->name }}">
                            @else
                                <div class="card-img no-image">No Image</div>
                            @endif
                        </a>
                        <div class="card-category">{{ $item->type->name ?? 'Uncategorized' }}</div>
                        <div class="card-title">{{ $item->name }}</div>
                        @if($item->brand)
                            <div class="card-brand">{{ $item->brand->name }}</div>
                        @endif
                        <div class="card-price-row">
                            @if($item->variants && $item->variants->count() > 0)
                                <div class="card-price">{{ number_format($item->variants->min('price')) }} Ks</div>
                                @if($item->variants->min('price') != $item->variants->max('price'))
                                    <div class="card-price-range">- {{ number_format($item->variants->max('price')) }} Ks</div>
                                @endif
                            @else
                                <div class="card-price">{{ number_format($item->price) }} Ks</div>
                            @endif
                        </div>
                        <button class="btn-add" onclick="addToCart({{ $item->id }})">
                            <span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                @empty
                    <div class="no-products-found">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>No products found</h3>
                        <p>Try adjusting your filters or search terms</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="pagination">
                    @if($items->onFirstPage())
                        <span class="page-btn disabled"><i class="fa-solid fa-angle-left"></i></span>
                    @else
                        <a href="{{ $items->previousPageUrl() }}" class="page-btn">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                    @endif

                    @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                        @if($page == $items->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $items->lastPage() || ($page >= $items->currentPage() - 1 && $page <= $items->currentPage() + 1))
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($items->hasMorePages())
                        <a href="{{ $items->nextPageUrl() }}" class="page-btn">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                    @else
                        <span class="page-btn disabled"><i class="fa-solid fa-angle-right"></i></span>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>

<!-- STYLE REMOVED - MOVED TO EXTERNAL CSS -->




}




}





}

.breadcrumb {


}

.breadcrumb a {


}

.shop-container {



}

@media (max-width: 768px) {
    .shop-container {
    
    }
    
    .sidebar {
    
    }
}

.sidebar {





}

.filter-group {



}

.filter-group:last-child {

}

.filter-title {




}

.filter-list {



}

.filter-list li {

}

.sub-filter-list {



}

.custom-checkbox,
.custom-radio {





}

.custom-checkbox input,
.custom-radio input {


}

.search-box {


}

.search-input {



    border-radius: 6px;

}

.search-btn {




    border-radius: 6px;

}

.price-range-wrapper {




}

.price-input {
    width: 100%;


    border-radius: 6px;

}

.apply-price-btn {
    width: 100%;




    border-radius: 6px;


}

.clear-filters-btn {
    width: 100%;




    border-radius: 6px;


    margin-top: 1rem;
}

.clear-filters-btn:hover {

}

.shop-topbar {

    justify-content: space-between;




}

.results-count {


}

.sort-wrapper select {


    border-radius: 6px;


}

.active-filters {





}

.active-filters-label {



}

.active-filter-tag {






    border-radius: 20px;


}

.active-filter-tag button {







}

.active-filter-tag button:hover {
    color: #dc3545;
}

.shop-grid {

    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));


}

.card {

    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.card-img-link {
    display: block;
    height: 200px;
    overflow: hidden;
}

.card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-img.no-image {



    justify-content: center;

}

.card-category {



}

.card-title {





}

.card-brand {



}

.card-price-row {



    padding: 0 0.75rem 0.75rem;
}

.card-price {



}

.card-price-range {


}

.btn-add {
    width: 100%;




    border-radius: 0 0 12px 12px;


    transition: background 0.3s;


    justify-content: center;

}

.btn-add:hover {
    background: #091a3a;
}

.no-products-found {
    grid-column: span 4;

    padding: 3rem;

}

.no-products-found i {
    font-size: 3rem;

}

.no-products-found h3 {


}

.pagination {

    justify-content: center;


}

.page-btn {
    min-width: 40px;
    height: 40px;


    justify-content: center;

    border-radius: 8px;



    transition: all 0.3s;
}

.page-btn:hover:not(.disabled):not(.active) {


}

.page-btn.active {



}

.page-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
<!-- END STYLE -->

<script>
function handleCategoryChange(checkbox) {
    // Uncheck other categories when "All" is selected
    if (checkbox.value === 'all') {
        if (checkbox.checked) {
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.type-checkbox').forEach(cb => cb.checked = false);
        }
    } else {
        // Uncheck "All" when specific category is selected
        document.querySelector('input[value="all"]').checked = false;
    }
    
    applyFilters();
}

function applyFilters() {
    const params = new URLSearchParams();
    
    // Search
    const searchValue = document.getElementById('searchInput').value.trim();
    if (searchValue) params.set('search', searchValue);
    
    // Categories
    const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
        .map(cb => cb.value);
    if (selectedCategories.length > 0) {
        params.set('category', selectedCategories.join(','));
    }
    
    // Types
    const selectedTypes = Array.from(document.querySelectorAll('.type-checkbox:checked'))
        .map(cb => cb.value);
    if (selectedTypes.length > 0) {
        params.set('type', selectedTypes.join(','));
    }
    
    // Brands
    const selectedBrands = Array.from(document.querySelectorAll('.brand-checkbox:checked'))
        .map(cb => cb.value);
    if (selectedBrands.length > 0) {
        params.set('brand', selectedBrands.join(','));
    }
    
    // Price
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    if (minPrice) params.set('min_price', minPrice);
    if (maxPrice) params.set('max_price', maxPrice);
    
    // Stock
    const stockRadio = document.querySelector('input[name="stock"]:checked');
    if (stockRadio && stockRadio.value) {
        params.set('stock', stockRadio.value);
    }
    
    // Sort
    const sortValue = document.getElementById('sortSelect').value;
    if (sortValue) params.set('sort', sortValue);
    
    // Redirect with filters
    window.location.href = '{{ route('shop.index') }}?' + params.toString();
}

function removeFilter(filterName) {
    const params = new URLSearchParams(window.location.search);
    params.delete(filterName);
    window.location.href = '{{ route('shop.index') }}?' + params.toString();
}

function removeFilterValue(filterName, value) {
    const params = new URLSearchParams(window.location.search);
    const currentValues = params.get(filterName)?.split(',') || [];
    const newValues = currentValues.filter(v => v != value);
    
    if (newValues.length > 0) {
        params.set(filterName, newValues.join(','));
    } else {
        params.delete(filterName);
    }
    
    window.location.href = '{{ route('shop.index') }}?' + params.toString();
}

function clearFilters() {
    window.location.href = '{{ route('shop.index') }}';
}

function addToCart(itemId) {
    // This will redirect to product detail for now
    // In the future, this can be an AJAX call
    window.location.href = '{{ route('shop.show', ':id') }}'.replace(':id', itemId);
}
</script>
@endsection