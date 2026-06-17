@extends('layouts.customer')

@section('title', 'My Wishlist - Campus Supply')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>My Wishlist</h1>
        <p>Items you've saved for later</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="wishlist-grid">
        @forelse($wishlistItems as $wishlistItem)
            @if($wishlistItem->item)
                <div class="wishlist-card">
                    <div class="wishlist-image">
                        @if($wishlistItem->item->image)
                            <img src="{{ asset('storage/' . $wishlistItem->item->image) }}" 
                                 alt="{{ $wishlistItem->item->name }}">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                        <button class="btn-remove" onclick="removeFromWishlist({{ $wishlistItem->id }})">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="wishlist-info">
                        <div class="item-category">
                            {{ $wishlistItem->item->type->name ?? 'Uncategorized' }}
                        </div>
                        <h3 class="item-name">
                            <a href="{{ route('shop.show', $wishlistItem->item->id) }}">
                                {{ $wishlistItem->item->name }}
                            </a>
                        </h3>
                        
                        <div class="item-price">
                            @if($wishlistItem->item->variants && $wishlistItem->item->variants->count() > 0)
                                {{ number_format($wishlistItem->item->variants->min('price')) }} Ks
                                @if($wishlistItem->item->variants->min('price') != $wishlistItem->item->variants->max('price'))
                                    - {{ number_format($wishlistItem->item->variants->max('price')) }} Ks
                                @endif
                            @else
                                {{ number_format($wishlistItem->item->price) }} Ks
                            @endif
                        </div>

                        <div class="item-actions">
                            <a href="{{ route('shop.show', $wishlistItem->item->id) }}" class="btn-view">
                                View Details
                            </a>
                            @if($wishlistItem->item->status === 'active')
                                <button class="btn-add-cart" onclick="addToCartFromWishlist({{ $wishlistItem->item->id }})">
                                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                </button>
                            @else
                                <span class="out-of-stock">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="empty-wishlist">
                <i class="fa-regular fa-heart"></i>
                <h3>Your wishlist is empty</h3>
                <p>Start adding items you love</p>
                <a href="{{ route('shop.index') }}" class="btn-start-shopping">Start Shopping</a>
            </div>
        @endforelse
    </div>

    @if($wishlistItems->hasPages())
        <div class="pagination">
            @if($wishlistItems->onFirstPage())
                <span class="page-btn disabled"><i class="fa-solid fa-angle-left"></i></span>
            @else
                <a href="{{ $wishlistItems->previousPageUrl() }}" class="page-btn">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            @endif

            @foreach($wishlistItems->getUrlRange(1, $wishlistItems->lastPage()) as $page => $url)
                @if($page == $wishlistItems->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @elseif($page == 1 || $page == $wishlistItems->lastPage() || ($page >= $wishlistItems->currentPage() - 1 && $page <= $wishlistItems->currentPage() + 1))
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($wishlistItems->hasMorePages())
                <a href="{{ $wishlistItems->nextPageUrl() }}" class="page-btn">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            @else
                <span class="page-btn disabled"><i class="fa-solid fa-angle-right"></i></span>
            @endif
        </div>
    @endif
</div>

<style>
.page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #666;
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

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.wishlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.wishlist-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.wishlist-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.wishlist-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.wishlist-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wishlist-image .no-image {
    width: 100%;
    height: 100%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.btn-remove {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 35px;
    height: 35px;
    background: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s;
    z-index: 2;
}

.btn-remove:hover {
    background: #dc3545;
    color: white;
}

.wishlist-info {
    padding: 1.25rem;
}

.item-category {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.item-name {
    font-size: 1.1rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.item-name a {
    text-decoration: none;
    color: inherit;
}

.item-name a:hover {
    color: var(--primary);
}

.item-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 1rem;
}

.item-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-view {
    flex: 1;
    padding: 0.75rem;
    background: #f5f5f5;
    color: #666;
    text-align: center;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-view:hover {
    background: #e9ecef;
}

.btn-add-cart {
    flex: 1;
    padding: 0.75rem;
    background: var(--secondary);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-add-cart:hover {
    background: #091a3a;
}

.out-of-stock {
    flex: 1;
    padding: 0.75rem;
    background: #fee2e2;
    color: #b91c1c;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
}

.empty-wishlist {
    text-align: center;
    padding: 4rem 2rem;
    color: #999;
    grid-column: span 4;
}

.empty-wishlist i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-wishlist h3 {
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.btn-start-shopping {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    background: var(--secondary);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.page-btn {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: var(--secondary);
    font-weight: 600;
    transition: all 0.3s;
}

.page-btn:hover:not(.disabled):not(.active) {
    border-color: var(--primary);
    background: #fffbeb;
}

.page-btn.active {
    background: var(--secondary);
    color: white;
    border-color: var(--secondary);
}

.page-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<script>
function removeFromWishlist(id) {
    if (confirm('Remove this item from wishlist?')) {
        fetch('{{ route('profile.remove-wishlist', ':id') }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function addToCartFromWishlist(itemId) {
    // Redirect to product detail to add to cart
    window.location.href = '{{ route('shop.show', ':id') }}'.replace(':id', itemId);
}
</script>
@endsection