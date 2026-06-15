// js/shop.js

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Elements များကို ရွေးချယ်ခြင်း
    const priceSlider = document.getElementById('priceSlider');
    const priceOutput = document.getElementById('priceOutput');

    // 2. Slider ရွှေ့တိုင်း အလုပ်လုပ်မည့် Function
    function updatePrice() {
        // Slider ရဲ့ လက်ရှိတန်ဖိုးကို ယူပြီး Text အဖြစ် ပြောင်းထည့်ခြင်း
        priceOutput.textContent = priceSlider.value + " Ks";
    }

    // 3. Event Listener ချိတ်ဆက်ခြင်း
    if (priceSlider && priceOutput) {
        // 'input' event သည် user က slider ကို ဖိဆွဲနေစဥ် အချိန်တိုင်း အလုပ်လုပ်သည်
        priceSlider.addEventListener('input', updatePrice);
    }

});