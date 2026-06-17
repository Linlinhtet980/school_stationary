// Home Page JavaScript - Extracted from UI Prototypes

document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider Logic
    let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let slideInterval;

    if (slides.length > 0) {
        function showSlide(n) {
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            slideIndex = (n + slides.length) % slides.length;
            slides[slideIndex].classList.add('active');
            if (dots[slideIndex]) {
                dots[slideIndex].classList.add('active');
            }
        }

        function moveSlide(n) { 
            showSlide(slideIndex + n); 
            resetInterval(); 
        }

        function currentSlide(n) { 
            showSlide(n); 
            resetInterval(); 
        }

        function startInterval() { 
            slideInterval = setInterval(() => { moveSlide(1); }, 5000); 
        }

        function resetInterval() { 
            clearInterval(slideInterval); 
            startInterval(); 
        }

        // Initialize slider
        startInterval();

        // Add navigation buttons
        const prevBtn = document.querySelector('.slider-nav button:first-child');
        const nextBtn = document.querySelector('.slider-nav button:last-child');

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                moveSlide(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                moveSlide(1);
            });
        }

        // Add dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                currentSlide(index);
            });
        });
    }
});