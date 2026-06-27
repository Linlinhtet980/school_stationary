@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/bestsellers.css') }}?v=3">
    <link rel="stylesheet" href="{{ asset('css/customer/views/sidebar_filter.css') }}?v=2">
@endpush

@section('title', 'Campus Supply - Shop')



@section('content')
<div class="shop-header">
    <h1>OUR BESTSELLERS</h1>
    <p>Shop the items our customers love the most.</p>
</div>

<div class="shop-container">
    <!-- Sidebar Filters -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>
    @include('customer.partials.sidebar_filter', ['action' => route('shop.bestsellers')])

    <!-- Main Product Area -->
    <main class="main-content">
        <div class="toolbar">
            <button type="button" class="mobile-filter-btn" onclick="toggleMobileSidebar()"><i class="fa-solid fa-filter"></i> Filters</button>
            <div class="results-count">Showing {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results</div>
            <div class="sort-by">
                <form action="{{ route('shop.bestsellers') }}" method="GET" id="sortForm">
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
                <button type="button" class="btn-add" style="margin-top: auto;" onclick="window.addToCart({{ $item->id }})"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
            </div>
            @empty
            <div class="inline-style-46">
                <h3>No products found</h3>
                <p>Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pagination inline-style-47" >
            {{ $items->links('vendor.pagination.pure-css') }}
        </div>

    </main>
</div>
    <script>
        function toggleMobileSidebar() {
            document.getElementById('sidebarFilter').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
            if (document.getElementById('sidebarFilter').classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection
