@extends('layouts.customer')

@section('title', 'B2S Deals - Campus Supply')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>B2S Deals</h1>
        <p>Back to School special offers and bundles</p>
    </div>

    <div class="deal-banner">
        <div class="deal-content">
            <h2>🎒 Back to School Sale</h2>
            <p>Get up to 30% off on selected items</p>
            <div class="deal-icons">
                <i class="fa-solid fa-pencil"></i>
                <i class="fa-solid fa-book"></i>
                <i class="fa-solid fa-ruler"></i>
                <i class="fa-solid fa-eraser"></i>
            </div>
        </div>
    </div>

    <div class="products-grid">
        @forelse($items as $item)
            <div class="product-card deal-card">
                <div class="product-badge">
                    <span class="badge deal">Deal</span>
                </div>
                <div class="product-image">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    @else
                        <div class="no-image">No Image</div>
                    @endif
                    <div class="product-actions">
                        <button class="action-btn" onclick="addToWishlist({{ $item->id }})">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        <a href="{{ route('shop.show', $item->id) }}" class="action-btn">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                    </div>
                </div>
                
                <div class="product-info">
                    <div class="product-category">{{ $item->type->name ?? 'Uncategorized' }}</div>
                    <h3 class="product-name">
                        <a href="{{ route('shop.show', $item->id) }}">{{ $item->name }}</a>
                    </h3>
                    <div class="product-price">
                        @if($item->variants && $item->variants->count() > 0)
                            {{ number_format($item->variants->min('price')) }} Ks
                            @if($item->variants->min('price') != $item->variants->max('price'))
                                - {{ number_format($item->variants->max('price')) }} Ks
                            @endif
                        @else
                            {{ number_format($item->price) }} Ks
                        @endif
                    </div>
                    <a href="{{ route('shop.show', $item->id) }}" class="btn-view-product">
                        View Deal
                    </a>
                </div>
            </div>
        @empty
            <div class="no-products">
                <i class="fa-solid fa-box-open"></i>
                <p>No deals available at the moment</p>
            </div>
        @endforelse
    </div>
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

.deal-banner {
    background: linear-gradient(135deg, var(--secondary) 0%, #1a3a6e 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
}

.deal-content h2 {
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
}

.deal-content p {
    font-size: 1.1rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.deal-icons {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    font-size: 1.5rem;
}

.deal-icons i {
    opacity: 0.8;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.deal-card {
    border: 2px solid var(--primary);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.product-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
}

.badge.deal {
    background: #ff6b6b;
    color: white;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.product-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-btn {
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
    transition: background 0.3s;
}

.action-btn:hover {
    background: var(--primary);
}

.product-info {
    padding: 1.25rem;
}

.product-category {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.product-name {
    font-size: 1.1rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.product-name a {
    text-decoration: none;
    color: inherit;
}

.product-name a:hover {
    color: var(--primary);
}

.product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ff6b6b;
    margin-bottom: 1rem;
}

.btn-view-product {
    display: inline-block;
    width: 100%;
    padding: 0.75rem;
    background: var(--secondary);
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-view-product:hover {
    background: #091a3a;
}

.no-products {
    text-align: center;
    padding: 3rem;
    color: #999;
    grid-column: span 4;
}

.no-products i {
    font-size: 3rem;
    margin-bottom: 1rem;
}
</style>
@endsection