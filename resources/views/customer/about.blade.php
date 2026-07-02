@extends('layouts.customer')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/about.css') }}">
@endpush

@section('title', 'About Us - Campus Supply')

@section('content')
<div class="about-container">
    
    <!-- 1. Hero Section -->
    <section class="about-hero">
        <h1>ABOUT <span>CAMPUS SUPPLY</span></h1>
        <p>ကျောင်းသားကျောင်းသူတိုင်း၏ ပညာရေးခရီးလမ်းတွင် အရည်အသွေးမြင့် စာရေးကိရိယာများကို အသက်သာဆုံးနှုန်းထားဖြင့် အဆင်ပြေချောမွေ့စွာ ဝယ်ယူနိုင်ရန် ပံ့ပိုးပေးနေပါသည်။</p>
    </section>

    <!-- 2. Story Section -->
    <section class="about-story">
        <div class="story-content">
            <h2>Our Story & Mission</h2>
            <p>Campus Supply ကို ကျောင်းသားကျောင်းသူများ၊ မိဘများနှင့် ဆရာ/ဆရာမများ ကျောင်းသုံးစာရေးကိရိယာများကို တစ်နေရာတည်းတွင် စုံစုံလင်လင်နှင့် ဈေးနှုန်းသက်သာစွာ ရွေးချယ်ဝယ်ယူနိုင်စေရန် ရည်ရွယ်၍ စတင်တည်ထောင်ခဲ့ခြင်းဖြစ်ပါသည်။</p>
            <p>ကျွန်ုပ်တို့၏ ရည်မှန်းချက်မှာ ရိုးရှင်းပါသည်။ အရည်အသွေးကောင်းမွန်သော ပစ္စည်းများကို အာမခံချက်ရှိရှိ ရောင်းချပေးခြင်း၊ အော်ဒါများကို အမြန်ဆုံး အိမ်ရောက်ပို့ဆောင်ပေးခြင်းနှင့် ကောင်းမွန်သော ဝန်ဆောင်မှုများဖြင့် သုံးစွဲသူများ စိတ်ကျေနပ်မှု အပြည့်အဝ ရရှိစေရန် လုပ်ဆောင်ပေးခြင်း ဖြစ်ပါသည်။</p>
        </div>
        <div class="story-image-container">
            <img src="{{ asset('storage/banners/back_to_school.png') }}" onerror="this.src='https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=600&q=80'" alt="Our Story" class="story-image">
        </div>
    </section>

    <!-- 3. Values Section -->
    <section class="about-values">
        <h2 class="about-values-title">Why Choose Us?</h2>
        <div class="about-values-grid">
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3>Premium Quality</h3>
                <p>တံဆိပ်အစစ်အမှန် ကျောင်းသုံးနှင့် ရုံးသုံးစာရေးကိရိယာများကိုသာ သေသေချာချာ စိစစ်ပြီး ရောင်းချပေးပါသည်။</p>
            </div>

            <div class="value-card">
                <div class="value-icon">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h3>Pocket-Friendly Prices</h3>
                <p>ကျောင်းသားကျောင်းသူများအတွက် သက်သာပြီး အသင့်တော်ဆုံးသော ဈေးနှုန်းနှုန်းထားများဖြင့် ရောင်းချပေးပါသည်။</p>
            </div>

            <div class="value-card">
                <div class="value-icon">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3>Fast Delivery</h3>
                <p>မှာယူထားသော ပစ္စည်းများကို သတ်မှတ်ရက်အတွင်း အမြန်ဆုံး အိမ်တိုင်ရာရောက် ပို့ဆောင်ပေးပါသည်။</p>
            </div>

            <div class="value-card">
                <div class="value-icon">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <h3>Special Bundle Saving</h3>
                <p>ကျောင်းဖွင့်ရာသီအထူး Combo Bundles များကို အထူးလျှော့ဈေးများဖြင့် ရောင်းချပေးပါသည်။</p>
            </div>
            
        </div>
    </section>

    <!-- 4. Stats Section -->
    <section class="about-stats">
        <div class="stat-item">
            <h3>10,000+</h3>
            <p>Happy Students</p>
        </div>
        <div class="stat-item">
            <h3>50+</h3>
            <p>Trusted Brands</p>
        </div>
        <div class="stat-item">
            <h3>5,000+</h3>
            <p>Completed Deliveries</p>
        </div>
        <div class="stat-item">
            <h3>99%</h3>
            <p>Satisfaction Rate</p>
        </div>
    </section>

    <!-- 5. Contact Section -->
    <section class="about-contact">
        <div class="contact-info-card">
            <h2>Get in Touch</h2>
            <div class="info-list">
                
                <div class="info-list-item">
                    <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="info-text">
                        <h4>Our Address</h4>
                        <p>No. (12) Thiri Avenue, Insein Township, Yangon, Myanmar</p>
                    </div>
                </div>

                <div class="info-list-item">
                    <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                    <div class="info-text">
                        <h4>Phone Number</h4>
                        <p>+95 9 890 647 598, +95 9 123 456 789</p>
                    </div>
                </div>

                <div class="info-list-item">
                    <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div class="info-text">
                        <h4>Email Address</h4>
                        <p>support@campussupply.com, info@campussupply.com</p>
                    </div>
                </div>

                <div class="info-list-item">
                    <div class="info-icon"><i class="fa-solid fa-clock"></i></div>
                    <div class="info-text">
                        <h4>Opening Hours</h4>
                        <p>Mon - Sat: 9:00 AM - 6:00 PM (Sunday: Closed)</p>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="map-container">
            <!-- Embedded Google Map targeting Yangon, Myanmar -->
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15270.83517208865!2d96.103987!3d16.890647!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c194b62db4c19b%3A0xe038e2d4157e1b54!2sInsein%20Township%2C%20Yangon!5e0!3m2!1sen!2smm!4v1717325200000!5m2!1sen!2smm" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

</div>
@endsection
