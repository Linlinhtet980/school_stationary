// js/home.js

document.addEventListener('DOMContentLoaded', () => {
    // 1. Slider Elements များကို ဆွဲယူခြင်း
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const btnPrev = document.getElementById('btnPrev');
    const btnNext = document.getElementById('btnNext');
    
    let currentSlideIndex = 0;
    const totalSlides = slides.length;

    // 2. Slide ပြောင်းပေးမည့် Function
    function showSlide(index) {
        // Index ကို limit အတွင်းရှိအောင် ထိန်းချုပ်ခြင်း (ဥပမာ - နောက်ဆုံးကနေ ကျော်သွားရင် အစပြန်ရောက်)
        if (index >= totalSlides) currentSlideIndex = 0;
        else if (index < 0) currentSlideIndex = totalSlides - 1;
        else currentSlideIndex = index;

        // Slide အားလုံးကို ဖျောက်ပြီး, လက်ရှိ Slide ကိုသာ ပြခြင်း
        slides.forEach(slide => slide.classList.remove('active'));
        slides[currentSlideIndex].classList.add('active');

        // Dot အားလုံးကို ဖျောက်ပြီး, လက်ရှိ Dot ကိုသာ Highlight လုပ်ခြင်း
        dots.forEach(dot => dot.classList.remove('active'));
        dots[currentSlideIndex].classList.add('active');
    }

    // 3. နောက်တစ်ပုံ သွားရန်
    function nextSlide() {
        showSlide(currentSlideIndex + 1);
    }

    // 4. အရှေ့တစ်ပုံ သွားရန်
    function prevSlide() {
        showSlide(currentSlideIndex - 1);
    }

    // 5. Event Listeners များ ချိတ်ဆက်ခြင်း
    if(btnNext) btnNext.addEventListener('click', nextSlide);
    if(btnPrev) btnPrev.addEventListener('click', prevSlide);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    // 6. Auto Play လုပ်ခြင်း (၅ စက္ကန့်တစ်ခါ အလိုအလျောက် ပြောင်းမည်)
    setInterval(nextSlide, 5000);
});