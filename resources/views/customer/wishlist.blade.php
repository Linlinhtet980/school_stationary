@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/wishlist.css') }}">
@endpush


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
                        <button class="wishlist-btn-remove" onclick="removeFromWishlist({{ $wishlistItem->id }})">
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




@endsection
@push('scripts')
<script>
function removeFromWishlist(id) {
    if (confirm('Remove this item from wishlist?')) {
        fetch('{{ route('profile.remove-wishlist', ':id') }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
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
@endpush
