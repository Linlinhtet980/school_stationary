@extends('layouts.customer')

@section('title', 'Bestsellers - Campus Supply')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Bestsellers</h1>
        <p>Our most popular products</p>
    </div>

    <div class="products-grid">
        @forelse($items as $item)
            <div class="product-card">
                <div class="product-badge">
                    <span class="badge bestseller">Bestseller</span>
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
                    <div class="product-stats">
                        <span><i class="fa-solid fa-cart-shopping"></i> {{ $item->order_count ?? 0 }} sold</span>
                    </div>
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
                        View Product
                    </a>
                </div>
            </div>
        @empty
            <div class="no-products">
                <i class="fa-solid fa-box-open"></i>
                <p>No bestsellers found</p>
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

.badge.bestseller {
    background: var(--primary);
    color: var(--secondary);
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

.product-stats {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.product-stats i {
    color: var(--primary);
    margin-right: 0.25rem;
}

.product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--secondary);
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