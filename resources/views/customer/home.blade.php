@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/home.css') }}?v={{ time() }}">
@endpush

@section('title', 'Campus Supply - Home')

@section('content')
<div class="hero-container">
    @if($banners->count() > 0)
    <div class="hero-slider" id="heroSlider">
        @foreach($banners as $banner)
        <div class="slide">
            @if($banner->image_path)
            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="banner-image">
            @endif
            <div class="hero-content">
                @if($banner->title)
                <h1>{{ $banner->title }}</h1>
                @endif
                @if($banner->description)
                <p>{{ $banner->description }}</p>
                @endif
                @if($banner->link)
                <button class="btn-shop" onclick="window.location.href='{{ $banner->link }}'">SHOP NOW &rarr;</button>
                @else
                <button class="btn-shop" onclick="window.location.href='{{ route('shop.index') }}'">SHOP NOW &rarr;</button>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Slider Controls -->
        <div class="slider-nav">
            <button class="prev-slide" onclick="moveSlide(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="next-slide" onclick="moveSlide(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="slider-dots" id="sliderDots">
            @foreach($banners as $index => $banner)
            <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index }})"></span>
            @endforeach
        </div>
    </div>
    @else
    <!-- Fallback static content if no banners -->
    <div class="hero-slider" id="heroSlider">
        <div class="slide active">
            <div class="hero-content">
                <h1>WELCOME TO CAMPUS SUPPLY</h1>
                <p>Your one-stop shop for school and office essentials</p>
                <button class="btn-shop" onclick="window.location.href='{{ route('shop.index') }}'">SHOP NOW &rarr;</button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- BRAND LOGOS SECTION -->
@if($brands->count() > 0)
<section class="brands-marquee-section">
    <div class="section-header">
        <h2 class="section-title">OUR PRODUCTS</h2>
    </div>
    <div class="marquee" style="padding: 1rem 0;">
        <div class="marquee-content" style="gap: 2rem;">
            <!-- First Set -->
            @foreach($brands as $brand)
            <div class="brand-item">
                @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo">
                @else
                <div class="brand-logo-text">{{ $brand->name }}</div>
                @endif
            </div>
            @endforeach
            <!-- Second Set for Infinite Loop -->
            @foreach($brands as $brand)
            <div class="brand-item">
                @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo">
                @else
                <div class="brand-logo-text">{{ $brand->name }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section dark-section">
    <div class="dark-shape1"></div>
    <div class="dark-shape2"></div>
    <div class="section-header inline-style-86" >
        <h2 class="section-title">OUR BESTSELLERS</h2>
    </div>
    <div class="arrows">
        <div class="arrow" onclick="scrollGrid('bestsellers-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
        <div class="arrow" onclick="scrollGrid('bestsellers-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
    </div>
    <div class="slider-grid inline-style-89" id="bestsellers-grid">
        @forelse($bestsellers as $item)
        <div class="card">
            <button type="button" class="btn-wishlist" onclick="window.addToWishlist({{ $item->id }})" title="Add to Wishlist">
                <i class="fa-regular fa-heart"></i>
            </button>
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
        <p class="inline-style-90">No bestsellers found.</p>
        @endforelse
    </div>
</section>

<!-- NEW ARRIVALS SECTION -->
<section class="section inline-style-91" >
    <div class="section-header">
        <h2 class="section-title">NEW ARRIVALS</h2>
    </div>
    <div class="arrows">
        <div class="arrow" onclick="scrollGrid('new-arrivals-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
        <div class="arrow" onclick="scrollGrid('new-arrivals-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
    </div>
    <div class="slider-grid" id="new-arrivals-grid">
        @forelse($newArrivals as $item)
        <div class="card inline-style-92" >
            <button type="button" class="btn-wishlist" onclick="window.addToWishlist({{ $item->id }})" title="Add to Wishlist">
                <i class="fa-regular fa-heart"></i>
            </button>
            <div class="inline-style-93">
                NEW
            </div>
            <img src="{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : asset('images/placeholder.jpg') }}" class="card-img" style="cursor:pointer;" onclick="window.location.href='{{ route('shop.show', $item->id) }}'" alt="{{ $item->name }}">
            <div class="card-title">{{ Str::limit($item->name, 30) }}</div>
            <div class="card-desc">{{ $item->type->name ?? 'Category' }}</div>
            <div class="card-price-row">
                <div class="card-price">{{ $item->price_range }}</div>
                <div class="stars">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                </div>
            </div>
            <button type="button" class="btn-add" style="margin-top: auto;" onclick="window.addToCart({{ $item->id }})"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
        </div>
        @empty
        <p>No new arrivals found.</p>
        @endforelse
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2 class="section-title">FEATURED ITEMS</h2>
    </div>
    <div class="arrows">
        <div class="arrow" onclick="scrollGrid('featured-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
        <div class="arrow" onclick="scrollGrid('featured-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
    </div>
    <div class="slider-grid" id="featured-grid">
        @forelse($featuredItems as $item)
        <div class="card">
            <button type="button" class="btn-wishlist" onclick="window.addToWishlist({{ $item->id }})" title="Add to Wishlist">
                <i class="fa-regular fa-heart"></i>
            </button>
            <img src="{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : asset('images/placeholder.jpg') }}" class="card-img" style="cursor:pointer;" onclick="window.location.href='{{ route('shop.show', $item->id) }}'" alt="{{ $item->name }}">
            <div class="card-title">{{ Str::limit($item->name, 30) }}</div>
            <div class="stars inline-style-94" >
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <div class="card-price inline-style-95" >{{ $item->price_range }}</div>
            <button type="button" class="btn-add" style="margin-top: auto;" onclick="window.addToCart({{ $item->id }})"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
        </div>
        @empty
        <p>No featured items.</p>
        @endforelse
    </div>
</section>

<section class="contact-section">
    <div class="contact-container">
        <h2>GET IN TOUCH</h2>
        <p>Have questions about your order or need help finding specific stationery? Send us a message!</p>
        <form class="contact-form" action="{{ route('contact.submit') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="4" placeholder="How can we help you?" required></textarea>
            <button type="submit">SEND MESSAGE</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Hero Slider Logic
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let slideInterval;

    function showSlide(n) {
        if(slides.length === 0) return;
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        slideIndex = (n + slides.length) % slides.length;
        slides[slideIndex].classList.add('active');
        dots[slideIndex].classList.add('active');
    }

    function moveSlide(n) { showSlide(slideIndex + n); resetInterval(); }
    function currentSlide(n) { showSlide(n); resetInterval(); }

    function startInterval() { slideInterval = setInterval(() => { moveSlide(1); }, 5000); }
    function resetInterval() { clearInterval(slideInterval); startInterval(); }
    if (slides.length > 0) startInterval();

    // Product Grid Slider Logic
    function scrollGrid(gridId, direction) {
        const grid = document.getElementById(gridId);
        if (!grid) return;
        const scrollAmount = grid.offsetWidth; // Scroll by visible width
        grid.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }
</script>
@endpush
