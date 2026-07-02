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
                <button type="button" class="btn-wishlist" onclick="window.addToWishlist({{ $item->id }})">
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
            <button type="button" class="btn-add" style="margin-top: auto;" onclick="window.addToCart({{ $related->id }})"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Reviews Section -->
<section class="section reviews-section" style="max-width: 1200px; margin: 3rem auto; padding: 0 1rem;">
    <div style="border-top: 1px solid #eee; padding-top: 2rem;">
        <h2 class="section-title" style="font-size: 1.5rem; color: var(--secondary); margin-bottom: 1.5rem;">Customer Reviews</h2>
        
        @if($item->reviews->isEmpty())
            <div style="background: #f9f9f9; padding: 2rem; border-radius: 12px; text-align: center; color: #666;">
                <i class="fa-regular fa-comment-dots" style="font-size: 3rem; margin-bottom: 1rem; color: #ccc; display: block;"></i>
                <h3>No reviews yet</h3>
                <p>Be the first to review this product!</p>
            </div>
        @else
            <!-- Star Filter Controls -->
            <div class="reviews-filter" style="display: flex; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap; align-items: center;">
                <span style="font-weight: 600; color: var(--secondary); margin-right: 0.5rem;">Filter by:</span>
                <button type="button" class="filter-pill active" onclick="filterReviews('all')" style="padding: 0.5rem 1rem; border: 1px solid #ddd; background: var(--secondary); border-radius: 20px; cursor: pointer; transition: all 0.3s; font-weight: 600; color: white;">All ({{ $item->reviews->count() }})</button>
                @for($rating = 5; $rating >= 1; $rating--)
                    @php $count = $item->reviews->where('rating', $rating)->count(); @endphp
                    <button type="button" class="filter-pill" onclick="filterReviews({{ $rating }})" style="padding: 0.5rem 1rem; border: 1px solid #ddd; background: white; border-radius: 20px; cursor: pointer; transition: all 0.3s; font-weight: 600; color: #666;" {{ $count == 0 ? 'disabled' : '' }}>
                        {{ $rating }} Star{{ $rating > 1 ? 's' : '' }} ({{ $count }})
                    </button>
                @endfor
            </div>

            <!-- Reviews List -->
            <div class="reviews-list" style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($item->reviews as $review)
                    <div class="review-card" data-rating="{{ $review->rating }}" style="background: white; border: 1px solid #eee; padding: 1.5rem; border-radius: 12px; transition: transform 0.3s;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                            <div>
                                <span style="font-weight: 800; color: var(--secondary); display: block; font-size: 1.05rem;">
                                    {{ $review->user->name ?? 'Campus Customer' }}
                                    @php
                                        $hasPurchased = false;
                                        if (auth()->check()) {
                                            $hasPurchased = \App\Models\Order::where('user_id', $review->user_id)
                                                ->where('status', 'completed')
                                                ->whereHas('items.itemVariant', function($query) use ($item) {
                                                    $query->where('item_id', $item->id);
                                                })->exists();
                                        }
                                    @endphp
                                    @if($hasPurchased)
                                        <span style="background: #e6fffa; color: #008080; font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; margin-left: 0.5rem; font-weight: 600; border: 1px solid #b2f5ea; display: inline-flex; align-items: center; gap: 3px;">
                                            <i class="fa-solid fa-circle-check"></i> Verified Purchase
                                        </span>
                                    @endif
                                </span>
                                <span style="font-size: 0.8rem; color: #999;">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <div style="color: #FFC107;">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star" style="font-size: 0.9rem;"></i>
                                    @else
                                        <i class="fa-regular fa-star" style="font-size: 0.9rem;"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p style="color: #4a5568; margin: 0; line-height: 1.6; font-size: 0.95rem;">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

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

    // Form validation and AJAX submission
    document.getElementById('addToCartForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent full page reload
        
        const variantId = document.getElementById('variant_id') ? document.getElementById('variant_id').value : null;
        
        if (document.getElementById('variant_id') && !variantId) {
            alert('Please select a variant');
            return false;
        }

        const quantity = parseInt(document.getElementById('quantity').value) || 1;
        
        // Wait for layout.js to be ready just in case
        if (window.addToCartAjax) {
            // If the item has no variants, we might need a fallback. But the form route is cart.add-item.
            // If variantId is set, use addToCartAjax
            if (variantId) {
                await window.addToCartAjax(variantId, quantity);
            } else {
                // For items without variants, we can use the regular addToCart or fetch variant
                // Wait, if it has no variants, the variant selector is hidden, so there's no variant_id input.
                // We'll just call window.addToCart(itemId) multiple times? No, we need quantity.
                // Let's use the first-variant endpoint to get it, or just submit the form if we don't have the logic ready.
                // Since most products have variants, or the first variant is auto-resolved, let's just submit the form if no variant_id exists.
                this.submit();
            }
        } else {
            this.submit();
        }
    });

    function filterReviews(rating) {
        // Toggle active class on buttons
        const pills = document.querySelectorAll('.filter-pill');
        pills.forEach(pill => {
            if (pill.hasAttribute('disabled')) return;
            pill.style.background = 'white';
            pill.style.color = '#666';
            pill.style.borderColor = '#ddd';
        });

        // Set active style for selected button
        const clickedButton = window.event.currentTarget;
        clickedButton.style.background = 'var(--secondary)';
        clickedButton.style.color = 'white';
        clickedButton.style.borderColor = 'var(--secondary)';

        // Filter cards
        const cards = document.querySelectorAll('.review-card');
        cards.forEach(card => {
            if (rating === 'all' || card.getAttribute('data-rating') == rating) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endpush
