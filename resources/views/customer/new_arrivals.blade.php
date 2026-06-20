@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/new_arrivals.css') }}">
@endpush

@section('title', 'Campus Supply - Shop')



@section('content')
<div class="shop-header">
    <h1>NEW ARRIVALS</h1>
    <p>Discover the latest additions to our store.</p>
</div>

<div class="shop-container">
    <!-- Sidebar Filters -->
    <aside class="sidebar">
        <form action="{{ route('shop.index') }}" method="GET" id="filterForm">
            <div class="filter-group">
                <div class="filter-title">Categories</div>
                <ul class="filter-list">
                    <li>
                        <label>
                            <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                            All Categories
                        </label>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <label>
                            <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                            {{ $category->name }}
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="filter-group">
                <div class="filter-title">Brands</div>
                <ul class="filter-list">
                    <li>
                        <label>
                            <input type="radio" name="brand" value="" {{ !request('brand') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                            All Brands
                        </label>
                    </li>
                    @foreach($brands as $brand)
                    <li>
                        <label>
                            <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                            {{ $brand->name }}
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="filter-group">
                <div class="filter-title">Price Range</div>
                <div class="filter-price">
                    <input type="number" name="min_price" placeholder="Min Ks" value="{{ request('min_price') }}">
                    <span>-</span>
                    <input type="number" name="max_price" placeholder="Max Ks" value="{{ request('max_price') }}">
                </div>
                <button type="submit" class="inline-style-96">Apply Filter</button>
            </div>
        </form>
    </aside>

    <!-- Main Product Area -->
    <main class="main-content">
        <div class="toolbar">
            <div class="results-count">Showing {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results</div>
            <div class="sort-by">
                <form action="{{ route('shop.index') }}" method="GET" id="sortForm">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                    <select name="sort" onchange="document.getElementById('sortForm').submit();">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort by Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Sort by Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Sort by Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="product-grid">
            @forelse($items as $item)
            <div class="card">
                <img src="{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : asset('images/placeholder.jpg') }}" class="card-img" style="cursor:pointer;" onclick="window.location.href='{{ route('shop.show', $item->id) }}'" alt="{{ $item->name }}">
                <div class="card-title">{{ Str::limit($item->name, 30) }}</div>
                <div class="card-desc">{{ $item->brand->name ?? 'No Brand' }}</div>
                <div class="card-price-row">
                    <div class="card-price">{{ $item->price_range }}</div>
                    <div class="stars">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                </div>
                <form action="{{ route('cart.add-item', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
                </form>
            </div>
            @empty
            <div class="inline-style-97">
                <h3>No products found</h3>
                <p>Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pagination inline-style-98" >
            {{ $items->links('vendor.pagination.pure-css') }}
        </div>

    </main>
</div>
@endsection
