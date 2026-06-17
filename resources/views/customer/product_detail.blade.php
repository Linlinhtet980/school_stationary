@extends('layouts.customer')

@section('title', $item->name . ' - Campus Supply')

@section('content')
<div class="page-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / 
        <a href="{{ route('shop.index') }}">Shop</a> / 
        {{ $item->name }}
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Product Detail Section -->
    <div class="product-detail-container">
        
        <!-- LEFT: Image Gallery -->
        <div class="gallery-wrapper">
            <div class="main-image-container">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" id="mainImage" class="main-image" alt="{{ $item->name }}">
                @else
                    <div class="no-image-large">No Image</div>
                @endif
            </div>
            @if($item->images && $item->images->count() > 0)
                <div class="thumbnail-list">
                    @foreach($item->images as $index => $image)
                        <div class="thumbnail {{ $index === 0 ? 'active' : '' }}" data-src="{{ asset('storage/' . $image->image_path) }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Thumb {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- RIGHT: Product Info -->
        <div class="product-info">
            @if($item->brand)
                <div class="product-brand">{{ $item->brand->name }}</div>
            @endif
            <h1 class="product-title">{{ $item->name }}</h1>
            
            <div class="product-price-box">
                @if($item->variants && $item->variants->count() > 0)
                    <span class="product-price" id="displayPrice">{{ number_format($item->variants->min('price')) }} Ks</span>
                    @if($item->variants->min('price') != $item->variants->max('price'))
                        <span class="product-price-range">- {{ number_format($item->variants->max('price')) }} Ks</span>
                    @endif
                @else
                    <span class="product-price">{{ number_format($item->price) }} Ks</span>
                @endif
            </div>

            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($averageRating))
                        <i class="fa-solid fa-star"></i>
                    @elseif($i - 0.5 <= $averageRating)
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @else
                        <i class="fa-regular fa-star"></i>
                    @endif
                @endfor
                <span>({{ $reviewCount }} Reviews)</span>
            </div>

            @if($item->description)
                <p class="product-short-desc">{{ Str::limit($item->description, 200) }}</p>
            @endif

            @if($item->variants && $item->variants->count() > 0)
                <!-- Variant Selection -->
                <div class="variants-section">
                    <div class="variant-title">Select Variant:</div>
                    <div class="variant-options">
                        @foreach($item->variants as $variant)
                            @if($variant->stock_quantity > 0)
                                <div class="variant-option" 
                                     data-variant-id="{{ $variant->id }}" 
                                     data-price="{{ $variant->price }}"
                                     data-stock="{{ $variant->stock_quantity }}"
                                     onclick="selectVariant(this)">
                                    <div class="variant-info">
                                        <div class="variant-name">
                                            {{ $variant->unit_label }}
                                            @if($variant->color) - {{ $variant->color }} @endif
                                            @if($variant->size) - {{ $variant->size }} @endif
                                        </div>
                                        <div class="variant-price">{{ number_format($variant->price) }} Ks</div>
                                        <div class="variant-stock">{{ $variant->stock_quantity }} in stock</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add to Cart Action -->
            @if($isAuthenticated)
            <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                @csrf
                <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $item->variants->first()->id ?? '' }}">
                
                <div class="action-row">
                    <div class="qty-selector">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                        <input type="number" name="quantity" class="qty-input" id="qtyInput" value="1" min="1" max="10" required>
                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                    <button type="submit" class="btn-add-large">
                        <i class="fa-solid fa-cart-shopping"></i> ADD TO CART
                    </button>
                </div>
            </form>
            @else
                <div class="action-row">
                    <div class="qty-selector">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                        <input type="number" class="qty-input" id="qtyInput" value="1" min="1" max="10" readonly>
                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                    <a href="{{ route('login') }}" class="btn-add-large">
                        <i class="fa-solid fa-cart-shopping"></i> LOGIN TO ADD
                    </a>
                </div>
                <p class="login-notice">Please login to add items to cart</p>
            @endif
            
            @if($item->variants && $item->variants->sum('stock_quantity') > 0)
                <div class="stock-status">
                    <i class="fa-solid fa-check-circle"></i> In Stock - Ready to ship
                </div>
            @else
                <div class="stock-status out-of-stock">
                    <i class="fa-solid fa-times-circle"></i> Out of Stock
                </div>
            @endif

            <div class="product-actions-secondary">
                <button class="action-secondary" onclick="addToWishlist({{ $item->id }})">
                    <i class="fa-regular fa-heart"></i> Add to Wishlist
                </button>
            </div>
        </div>
    </div>

    <!-- TABS SECTION -->
    <div class="tabs-container">
        <div class="tab-headers">
            <button class="tab-btn active" onclick="showTab('descTab')">Description</button>
            <button class="tab-btn" onclick="showTab('specTab')">Specifications</button>
            <button class="tab-btn" onclick="showTab('reviewTab')">Reviews ({{ $reviewCount }})</button>
        </div>
        
        <div class="tab-content active" id="descTab">
            <h3>Product Description</h3>
            @if($item->description)
                <p>{{ nl2br($item->description) }}</p>
            @else
                <p>No description available for this product.</p>
            @endif
        </div>
        
        <div class="tab-content" id="specTab">
            <h3>Specifications</h3>
            <ul class="spec-list">
                @if($item->type)
                    <li><strong>Type:</strong> {{ $item->type->name }}</li>
                @endif
                @if($item->brand)
                    <li><strong>Brand:</strong> {{ $item->brand->name }}</li>
                @endif
                @if($item->variants && $item->variants->count() > 0)
                    <li><strong>Available Variants:</strong></li>
                    @foreach($item->variants as $variant)
                        <li>
                            - {{ $variant->unit_label }} 
                            @if($variant->color) ({{ $variant->color }}) @endif
                            @if($variant->size) ({{ $variant->size }}) @endif
                            : {{ number_format($variant->price) }} Ks
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <div class="tab-content" id="reviewTab">
            <h3>Customer Reviews</h3>
            @if($item->reviews && $item->reviews->count() > 0)
                <div class="reviews-list">
                    @foreach($item->reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                            @if($review->comment)
                                <p class="review-comment">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p>No reviews yet. Be the first to review this product!</p>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedItems && $relatedItems->count() > 0)
        <div class="related-products-section">
            <h2>Related Products</h2>
            <div class="related-products-grid">
                @foreach($relatedItems as $relatedItem)
                    <div class="related-product-card">
                        <div class="related-product-image">
                            @if($relatedItem->image)
                                <img src="{{ asset('storage/' . $relatedItem->image) }}" alt="{{ $relatedItem->name }}">
                            @else
                                <div class="no-image-small">No Image</div>
                            @endif
                        </div>
                        <div class="related-product-info">
                            <h4>{{ $relatedItem->name }}</h4>
                            <div class="related-product-price">
                                @if($relatedItem->variants && $relatedItem->variants->count() > 0)
                                    {{ number_format($relatedItem->variants->min('price')) }} Ks
                                @else
                                    {{ number_format($relatedItem->price) }} Ks
                                @endif
                            </div>
                            <a href="{{ route('shop.show', $relatedItem->id) }}" class="btn-view-related">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<style>
.page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.breadcrumb {
    color: #A0AEC0;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.breadcrumb a {
    color: var(--primary);
    text-decoration: none;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.product-detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

@media (max-width: 768px) {
    .product-detail-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

.gallery-wrapper {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.main-image-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
}

.main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.no-image-large {
    width: 100%;
    height: 400px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.thumbnail-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    gap: 0.5rem;
}

.thumbnail {
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumbnail.active {
    border-color: var(--primary);
}

.thumbnail img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}

.product-info {
    padding: 1rem 0;
}

.product-brand {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.product-title {
    font-size: 1.75rem;
    color: var(--secondary);
    margin-bottom: 1rem;
    line-height: 1.2;
}

.product-price-box {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.product-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary);
}

.product-price-range {
    color: #666;
    font-size: 1.25rem;
}

.stars {
    color: var(--primary);
    font-size: 1rem;
    margin-bottom: 1rem;
}

.stars span {
    color: #A0AEC0;
    font-size: 0.9rem;
    margin-left: 0.5rem;
}

.product-short-desc {
    color: #333;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.variants-section {
    margin-bottom: 1.5rem;
}

.variant-title {
    font-weight: 600;
    color: var(--secondary);
    margin-bottom: 0.75rem;
}

.variant-options {
    display: grid;
    gap: 0.5rem;
}

.variant-option {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.3s;
}

.variant-option:hover,
.variant-option.selected {
    border-color: var(--primary);
    background: #fffbeb;
}

.variant-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.variant-name {
    font-weight: 600;
    color: var(--secondary);
}

.variant-price {
    font-weight: 700;
    color: var(--primary);
}

.variant-stock {
    font-size: 0.85rem;
    color: #666;
}

.action-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.qty-selector {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    width: 40px;
    height: 40px;
    background: white;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 1.25rem;
}

.qty-btn:hover {
    background: #f5f5f5;
}

.qty-input {
    width: 60px;
    height: 40px;
    text-align: center;
    border: none;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    font-weight: 600;
}

.btn-add-large {
    flex: 1;
    padding: 0 1.5rem;
    background: var(--secondary);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-add-large:hover {
    background: #091a3a;
}

.stock-status {
    color: #047857;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.stock-status.out-of-stock {
    color: #dc3545;
}

.product-actions-secondary {
    display: flex;
    gap: 1rem;
}

.action-secondary {
    padding: 0.75rem 1.5rem;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    color: var(--secondary);
    transition: all 0.3s;
}

.action-secondary:hover {
    border-color: var(--primary);
    background: #fffbeb;
}

.tabs-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 3rem;
}

.tab-headers {
    display: flex;
    gap: 2rem;
    border-bottom: 2px solid #eee;
    margin-bottom: 1.5rem;
}

.tab-btn {
    padding: 0.75rem 0;
    background: none;
    border: none;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    position: relative;
}

.tab-btn.active {
    color: var(--secondary);
}

.tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.tab-content h3 {
    color: var(--secondary);
    margin-bottom: 1rem;
}

.spec-list {
    list-style: none;
    padding: 0;
}

.spec-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
    color: #333;
}

.spec-list li:last-child {
    border-bottom: none;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.review-item {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.review-rating {
    color: var(--primary);
}

.review-date {
    color: #666;
    font-size: 0.85rem;
}

.review-comment {
    color: #333;
    margin: 0;
}

.related-products-section {
    margin-top: 3rem;
}

.related-products-section h2 {
    color: var(--secondary);
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

.related-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.related-product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.related-product-card:hover {
    transform: translateY(-5px);
}

.related-product-image {
    height: 180px;
    overflow: hidden;
}

.related-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image-small {
    width: 100%;
    height: 180px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.related-product-info {
    padding: 1rem;
}

.related-product-info h4 {
    color: var(--secondary);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.related-product-price {
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 0.75rem;
}

.btn-view-related {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: var(--secondary);
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
}

.btn-view-related:hover {
    background: #e6bd00;
}
</style>

<script>
// Thumbnail functionality
document.querySelectorAll('.thumbnail').forEach(thumb => {
    thumb.addEventListener('click', function() {
        const mainImage = document.getElementById('mainImage');
        const src = this.getAttribute('data-src');
        
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        if (mainImage.tagName === 'IMG') {
            mainImage.src = src;
        }
    });
});

// Variant selection
function selectVariant(element) {
    document.querySelectorAll('.variant-option').forEach(opt => opt.classList.remove('selected'));
    element.classList.add('selected');
    
    const variantId = element.getAttribute('data-variant-id');
    const price = element.getAttribute('data-price');
    const stock = element.getAttribute('data-stock');
    
    document.getElementById('selectedVariantId').value = variantId;
    document.getElementById('displayPrice').textContent = parseInt(price).toLocaleString() + ' Ks';
    document.getElementById('qtyInput').max = stock;
}

// Quantity adjustment
function changeQty(change) {
    const input = document.getElementById('qtyInput');
    let newValue = parseInt(input.value) + change;
    
    if (newValue >= 1 && newValue <= 10) {
        input.value = newValue;
    }
}

// Tab functionality
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

// Wishlist placeholder
function addToWishlist(itemId) {
    // This will be implemented when wishlist functionality is added
    alert('Wishlist functionality will be implemented soon!');
}

// Add to cart form validation
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    const variantId = document.getElementById('selectedVariantId').value;
    if (!variantId) {
        e.preventDefault();
        alert('Please select a variant');
    }
});
</script>
@endsection