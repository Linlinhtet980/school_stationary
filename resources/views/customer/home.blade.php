@extends('layouts.customer')

@section('title', 'Campus Supply - Premium Store')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/home.css') }}">
@endpush

@section('content')
    <!-- Hero Slider Section -->
    <div class="hero-container">
        <div class="hero-slider" id="heroSlider">
            <!-- Slide 1 -->
            @forelse($banners as $index => $banner)
                <div class="slide {{ $index === 0 ? 'active' : '' }}"
                    style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('{{ asset('storage/' . $banner->image_path) }}'); background-size: cover; background-position: center;">

                    <div class="hero-content">
                        @if($banner->title)
                            <h1 class="text-white">{{ $banner->title }}</h1>
                        @endif

                        @if($banner->link)
                            <a href="{{ $banner->link }}" class="btn-shop" target="_blank">SHOP NOW &rarr;</a>
                        @endif
                    </div>
                </div>
            @empty

                <div class="slide slide-promo active">
                    <div class="hero-bg-shape1"></div>
                    <div class="hero-bg-shape2"></div>
                    <div class="hero-content">
                        <h1 class="text-secondary">WELCOME TO CAMPUS SUPPLY</h1>
                        <p class="text-dark">Gear up for the new semester! Premium stationery, quality supplies, and huge
                            savings.</p>
                        <a href="{{ route('shop.index') }}" class="btn-shop">SHOP NOW &rarr;</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($banners && $banners->count() > 1)
            <div class="slider-nav">
                <button onclick="moveSlide(-1)">&#10094;</button>
                <button onclick="moveSlide(1)">&#10095;</button>
            </div>

            <div class="slider-dots">
                @foreach($banners as $index => $banner)
                    <div class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index }})"></div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="infinite-carousel-section">
        <div class="marquee">
            <div class="marquee-content">
                <!-- Brand Logos -->
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 1"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 2"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 3"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 4"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 5"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 6"></div>
                <!-- Duplicate for continuous scroll -->
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 1"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 2"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B1%80%E0%B8%B1%E0%B8%93%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B9%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                    class="card-img" alt="Brand 3"></div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">FEATURED PRODUCTS</h2>
            <div class="arrows">
                <button class="arrow">&larr;</button>
                <button class="arrow">&rarr;</button>
            </div>
        </div>
        
        <div class="product-grid">
            @forelse($featuredItems ?? [] as $item)
                <div class="card">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="card-img">
                    @else
                        <img src="https://via.placeholder.com/200" alt="{{ $item->name }}" class="card-img">
                    @endif
                    <h3 class="card-title">{{ $item->name }}</h3>
                    <p class="card-desc">{{ $item->type->name ?? '' }}</p>
                    <div class="card-price-row">
                        <span class="card-price">
                            @if($item->variants && $item->variants->count() > 0)
                                {{ number_format($item->variants->min('price')) }} Ks
                            @else
                                {{ number_format($item->price) }} Ks
                            @endif
                        </span>
                    </div>
                    @if($item->status === 'active')
                        <button class="btn-card btn-add" data-id="{{ $item->id }}">Add to Cart</button>
                    @else
                        <button class="btn-card" disabled>Out of Stock</button>
                    @endif
                </div>
            @empty
                <p>No featured products available.</p>
            @endforelse
        </div>
    </div>

    <!-- Categories Section -->
    @if($categories && $categories->count() > 0)
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">SHOP BY CATEGORY</h2>
            <a href="{{ route('shop.index') }}" class="view-all-link">View All &rarr;</a>
        </div>

        <div class="categories-slider">
            @foreach($categories as $category)
                <a href="{{ route('shop.index') }}?category={{ $category->id }}" class="category-card">
                    <div class="category-image">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <div class="category-placeholder">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        @endif
                    </div>
                    <div class="category-name">{{ $category->name }}</div>
                    <div class="category-count">{{ $category->items->where('status', 'active')->count() }} items</div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('js/customer/home.js') }}"></script>
@endpush


