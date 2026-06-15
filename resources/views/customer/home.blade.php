<!-- 💡 Layout ဖိုင်ကို လှမ်းခေါ်ခြင်း -->
@extends('layouts.customer')

<!-- 💡 ခေါင်းစဉ်သတ်မှတ်ခြင်း -->
@section('title', 'Campus Supply - Premium Store')

<!-- 💡 Layout ၏ @yield('content') နေရာတွင် အစားထိုးမည့် အကြောင်းအရာများ -->
@section('content')

    <!-- Hero Slider Section -->
    <div class="hero-container">
        <div class="hero-slider" id="heroSlider">
            <!-- Slide 1 -->
            @forelse($banners as $index => $banner)
                <div class="slide {{ $index === 0 ? 'active' : '' }}"
                    style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('{{ asset('storage/' . $banner->image_path) }}'); background-size: cover; background-position: center;">

                    <div class="hero-content">
                        <!-- Banner Title (Null Safety ဖြစ်အောင် ကာကွယ်ထားပါသည်) -->
                        @if($banner->title)
                            <h1 class="text-white">{{ $banner->title }}</h1>
                        @endif

                        <!-- Banner Link (ရှိမှသာ ခလုတ်ပြမည်) -->
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
                        <a href="#" class="btn-shop">SHOP NOW &rarr;</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="infinite-carousel-section">
        

        <div class="marquee">
            <div class="marquee-content">
                <!-- 💡 ပထမအသုတ် Logos (Slide-1) -->
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="shipping"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>

                <!-- 💡 ဒုတိယအသုတ် Logos (အဆက်မပြတ် ပတ်နေစေရန် Clone လုပ်ထားခြင်း) -->
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>

                <!-- 💡 ဒုတိယအသုတ် Logos (အဆက်မပြတ် ပတ်နေစေရန် Clone လုပ်ထားခြင်း) -->
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
                <div class="marquee-item"><img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook"></div>
            </div>
        </div>
    </div>

    <!-- Bestsellers Dark Section -->
    <section class="section dark-section">
        <div class="dark-shape1"></div>
        <div class="dark-shape2"></div>
        <div class="section-header">
            <h2 class="section-title">OUR BESTSELLERS</h2>
        </div>
        <div class="product-grid">
            <!-- Product Card 1 -->
            <div class="card">
                <a href="#" class="card-img-link">
                    <img src="https://media.istockphoto.com/id/2203911891/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B9%80%E0%B8%9E%E0%B8%B4%E0%B9%88%E0%B8%A1%E0%B8%9B%E0%B8%A3%E0%B8%B0%E0%B8%AA%E0%B8%B4%E0%B8%97%E0%B8%98%E0%B8%B4%E0%B8%A0%E0%B8%B2%E0%B8%9E%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%8B%E0%B8%B7%E0%B9%89%E0%B8%AD%E0%B8%9C%E0%B8%A5%E0%B8%B4%E0%B8%95%E0%B8%A0%E0%B8%B1%E0%B8%93%E0%B8%91%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B9%82%E0%B8%8B%E0%B8%A5%E0%B8%B9%E0%B8%8A%E0%B8%B1%E0%B8%99%E0%B8%9A%E0%B8%A3%E0%B8%B4%E0%B8%81%E0%B8%B2%E0%B8%A3-crm-customer-relationship.jpg?s=1024x1024&w=is&k=20&c=XugWKlnnr8Xfh2z2xQsum6Kx8s6AxNEQcAcVtgno1XA="
                        class="card-img" alt="Notebook">
                </a>
                <div class="card-title">Eco-Friendly A5 Notebook</div>
                <div class="card-desc">Navy/Yellow</div>
                <div class="card-price-row">
                    <div class="card-price">$8.99</div>
                </div>
                <button class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
            </div>
        </div>
    </section>

@endsection