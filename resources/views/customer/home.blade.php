@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/home.css') }}">
@endpush

@section('title', 'Campus Supply - Home')

@section('content')
<div class="hero-container">
    <div class="hero-slider" id="heroSlider">
        <!-- Slide 1: Promotions -->
        <div class="slide active inline-style-66" >
            <div class="hero-bg-shape1"></div>
            <div class="hero-bg-shape2"></div>
            <div class="hero-content">
                <h1 class="inline-style-67">BACK TO SCHOOL<br>PROMOTIONS</h1>
                <p class="inline-style-68">Gear up for the new semester! Premium stationery,<br>quality supplies, and huge savings.</p>
                <button class="btn-shop" onclick="window.location.href='{{ route('shop.index') }}'">SHOP NOW &rarr;</button>
            </div>
        </div>
        <!-- Slide 2: New Products -->
        <div class="slide inline-style-69" >
            <div class="hero-bg-shape3 inline-style-70" ></div>
            <div class="hero-bg-shape4 inline-style-71" ></div>
            <div class="hero-content">
                <h1 class="inline-style-72">NEW ARRIVALS<br>JUST LANDED</h1>
                <p class="inline-style-73">Discover our latest collection of modern<br>office and school essentials.</p>
                <button class="btn-shop inline-style-74"  onclick="window.location.href='{{ route('shop.new-arrivals') }}'">VIEW NEW PRODUCTS &rarr;</button>
            </div>
        </div>
        <!-- Slide 3: Information -->
        <div class="slide inline-style-75" >
            <div class="hero-bg-shape4 inline-style-76" ></div>
            <div class="hero-content">
                <h1 class="inline-style-77">FREE SHIPPING<br>NATIONWIDE</h1>
                <p class="inline-style-78">Enjoy free delivery on all orders over 50,000 Ks.<br>Fast, reliable, and secure.</p>
                <button class="btn-shop inline-style-79"  onclick="window.location.href='{{ route('shop.index') }}'">LEARN MORE &rarr;</button>
            </div>
        </div>
        <!-- Slide 4: Expiring/Clearance -->
        <div class="slide inline-style-80" >
            <div class="hero-bg-shape1 inline-style-81" ></div>
            <div class="hero-bg-shape2 inline-style-82" ></div>
            <div class="hero-content">
                <h1 class="inline-style-83">CLEARANCE SALE<br>UP TO 70% OFF</h1>
                <p class="inline-style-84">Last chance to grab these expiring items before<br>they are gone forever!</p>
                <button class="btn-shop inline-style-85"  onclick="window.location.href='{{ route('shop.b2s-deals') }}'">SHOP CLEARANCE &rarr;</button>
            </div>
        </div>

        <!-- Slider Controls -->
        <div class="slider-nav">
            <button class="prev-slide" onclick="moveSlide(-1)"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="next-slide" onclick="moveSlide(1)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="slider-dots" id="sliderDots">
            <span class="dot active" onclick="currentSlide(0)"></span>
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
    </div>
</div>

<section class="section dark-section">
    <div class="dark-shape1"></div>
    <div class="dark-shape2"></div>
    <div class="section-header inline-style-86" >
        <h2 class="section-title">OUR BESTSELLERS</h2>
        <div class="arrows">
            <div class="arrow" onclick="scrollGrid('bestsellers-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="arrow" onclick="scrollGrid('bestsellers-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </div>
    <div class="slider-grid inline-style-89" id="bestsellers-grid">
        @forelse($bestsellers as $item)
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
            <form action="{{ route('cart.add-item', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
            </form>
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
        <div class="arrows">
            <div class="arrow" onclick="scrollGrid('new-arrivals-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="arrow" onclick="scrollGrid('new-arrivals-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </div>
    <div class="slider-grid" id="new-arrivals-grid">
        @forelse($newArrivals as $item)
        <div class="card inline-style-92" >
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
            <form action="{{ route('cart.add-item', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
            </form>
        </div>
        @empty
        <p>No new arrivals found.</p>
        @endforelse
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2 class="section-title">FEATURED ITEMS</h2>
        <div class="arrows">
            <div class="arrow" onclick="scrollGrid('featured-grid', -1)"><i class="fa-solid fa-chevron-left"></i></div>
            <div class="arrow" onclick="scrollGrid('featured-grid', 1)"><i class="fa-solid fa-chevron-right"></i></div>
        </div>
    </div>
    <div class="slider-grid" id="featured-grid">
        @forelse($featuredItems as $item)
        <div class="card">
            <img src="{{ $item->images->first() ? asset('storage/' . $item->images->first()->image_path) : asset('images/placeholder.jpg') }}" class="card-img" style="cursor:pointer;" onclick="window.location.href='{{ route('shop.show', $item->id) }}'" alt="{{ $item->name }}">
            <div class="card-title">{{ Str::limit($item->name, 30) }}</div>
            <div class="stars inline-style-94" >
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <div class="card-price inline-style-95" >{{ $item->price_range }}</div>
            <form action="{{ route('cart.add-item', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
            </form>
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
