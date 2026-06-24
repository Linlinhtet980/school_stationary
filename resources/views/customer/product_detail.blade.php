@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/product_detail.css') }}">
@endpush

@section('title', 'Campus Supply - ' . $item->name)



@section('content')
<div class="breadcrumb">
    <a href="{{ route('home') }}">Home</a> / <a href="{{ route('shop.index') }}">Products</a> / <a href="{{ route('shop.index', ['category' => $item->type->category_id ?? '']) }}">{{ $item->type->name ?? 'Category' }}</a> / {{ $item->name }}
</div>

<div class="product-container">
    <!-- Image Section -->
    <div class="product-image-section">
        <img id="mainImage" src="{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : asset('images/placeholder.jpg') }}" class="main-image" alt="{{ $item->name }}">
        @if($item->images->count() > 1)
        <div class="thumbnail-list">
            @foreach($item->images as $index => $image)
            <img src="{{ asset('storage/' . $image->image_path) }}" class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ asset('storage/' . $image->image_path) }}')">
            @endforeach
        </div>
        @endif
    </div>

    <!-- Info Section -->
    <div class="product-info-section">
        <div class="brand-name">{{ $item->brand->name ?? 'Campus Supply' }}</div>
        <h1 class="product-title">{{ $item->name }}</h1>
        
        <div class="reviews-summary">
            <div class="stars">
                @for($i=1; $i<=5; $i++)
                    @if($i <= round($averageRating))
                        <i class="fa-solid fa-star"></i>
                    @else
                        <i class="fa-regular fa-star"></i>
                    @endif
                @endfor
            </div>
            <div class="review-count">{{ number_format($averageRating, 1) }} ({{ $reviewCount }} Reviews)</div>
        </div>

        @php
            $minPrice = $item->variants->min('price') ?? $item->price;
            $maxPrice = $item->variants->max('price') ?? $item->price;
            $totalStock = $item->variants->sum('stock_quantity') ?? 0;
        @endphp

        <div class="price-section">
            <div class="current-price" id="displayPrice">
                {{ number_format($minPrice) }} Ks {{ $minPrice != $maxPrice ? ' - ' . number_format($maxPrice) . ' Ks' : '' }}
            </div>
            @if($totalStock > 0)
                <div class="stock-status in-stock" id="displayStock">In Stock ({{ $totalStock }})</div>
            @else
                <div class="stock-status out-of-stock" id="displayStock">Out of Stock</div>
            @endif
        </div>

        <p class="description">
            {{ $item->description }}
        </p>

        <form action="{{ route('cart.add-item', $item->id) }}" method="POST" id="addToCartForm">
            @csrf
            
            @if($item->variants->count() > 0)
            <div class="options-group">
                <div class="options-title">Color / Variant: <span id="selectedVariantName" class="inline-style-102">Select an option</span></div>
                <div class="variant-pills-container">
                    @foreach($item->variants as $variant)
                        @php
                            $parts = array_filter([$variant->color, $variant->size, $variant->unit_label]);
                            $variantName = empty($parts) ? 'Standard' : implode(' | ', $parts);
                        @endphp
                        <div class="variant-pill" 
                             onclick="selectVariant({{ $variant->id }}, '{{ addslashes($variantName) }}', {{ $variant->price }}, {{ $variant->stock_quantity }})"
                             id="variant-btn-{{ $variant->id }}"
                             title="{{ $variantName }}">
                             <span class="variant-name">{{ $variantName }}</span>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="variant_id" id="variant_id" value="" required>
            </div>
            @endif

            <div class="actions-row">
                <div class="qty-selector">
                    <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                    <input type="text" name="quantity" id="quantity" class="qty-input" value="1" readonly>
                    <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                </div>
                <button type="submit" class="btn-add-huge" id="addToCartBtn" {{ $totalStock == 0 ? 'disabled' : '' }}>
                    <i class="fa-solid fa-cart-shopping inline-style-104" ></i> Add to Cart
                </button>
                <button type="button" class="btn-wishlist">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@if(isset($relatedItems) && $relatedItems->count() > 0)
<section class="section related-products-section">
    <div class="section-header">
        <h2 class="section-title">YOU MAY ALSO LIKE</h2>
        <div class="arrows">
            <div class="arrow" onclick="scrollGrid('related-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="arrow" onclick="scrollGrid('related-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </div>
    <div class="slider-grid" id="related-grid">
        @foreach($relatedItems as $related)
        <div class="card" style="position: relative;">
            <!-- Optional: You can add a badge here if needed like NEW -->
            <img src="{{ $related->images->first() ? asset('storage/' . $related->images->first()->image_path) : asset('images/placeholder.jpg') }}" class="card-img" style="cursor:pointer;" onclick="window.location.href='{{ route('shop.show', $related->id) }}'" alt="{{ $related->name }}">
            <div class="card-title">{{ Str::limit($related->name, 30) }}</div>
            <div class="card-desc">{{ $related->brand->name ?? 'Campus Supply' }}</div>
            <div class="card-price-row">
                <div class="card-price">{{ $related->price_range ?? number_format($related->price).' Ks' }}</div>
                <div class="stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
            </div>
            <form action="{{ route('cart.add-item', $related->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
            </form>
        </div>
        @endforeach
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    function scrollGrid(gridId, direction) {
        const grid = document.getElementById(gridId);
        if (!grid) return;
        const scrollAmount = grid.offsetWidth;
        grid.scrollBy({ left: scrollAmount * direction, behavior: 'smooth' });
    }

    function changeImage(element, src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    let currentStock = {{ $totalStock }};
    
    function selectVariant(id, name, price, stock) {
        // Update UI
        document.querySelectorAll('.variant-pill').forEach(btn => btn.classList.remove('active'));
        document.getElementById('variant-btn-' + id).classList.add('active');
        
        // Update Form & Info
        document.getElementById('variant_id').value = id;
        document.getElementById('selectedVariantName').innerText = name;
        document.getElementById('displayPrice').innerText = new Intl.NumberFormat().format(price) + ' Ks';
        
        currentStock = stock;
        const stockEl = document.getElementById('displayStock');
        const addBtn = document.getElementById('addToCartBtn');
        
        if (stock > 0) {
            stockEl.className = 'stock-status in-stock';
            stockEl.innerText = 'In Stock (' + stock + ')';
            addBtn.disabled = false;
        } else {
            stockEl.className = 'stock-status out-of-stock';
            stockEl.innerText = 'Out of Stock';
            addBtn.disabled = true;
        }
        
        // Reset qty
        document.getElementById('quantity').value = 1;
    }

    function updateQty(change) {
        const input = document.getElementById('quantity');
        let val = parseInt(input.value) + change;
        
        // Need to select variant first if there are variants
        if (document.getElementById('variant_id') && !document.getElementById('variant_id').value) {
            alert('Please select a variant first');
            return;
        }
        
        if (val >= 1 && val <= currentStock) {
            input.value = val;
        }
    }

    // Form validation before submit
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        if (document.getElementById('variant_id') && !document.getElementById('variant_id').value) {
            alert('Please select a variant');
            e.preventDefault();
            return false;
        }
        // Let layout.js handle the actual AJAX submission and cart drawer opening
    });
</script>
@endpush
