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

// Filter Functions
function applyFilters() {
    const url = new URL(window.location.href);
    
    // Get selected categories
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox:checked');
    const categories = Array.from(categoryCheckboxes).map(cb => cb.value).join(',');
    if (categories) url.searchParams.set('category', categories);
    
    // Get selected types
    const typeCheckboxes = document.querySelectorAll('.type-checkbox:checked');
    const types = Array.from(typeCheckboxes).map(cb => cb.value).join(',');
    if (types) url.searchParams.set('type', types);
    
    // Get selected brands
    const brandCheckboxes = document.querySelectorAll('.brand-checkbox:checked');
    const brands = Array.from(brandCheckboxes).map(cb => cb.value).join(',');
    if (brands) url.searchParams.set('brand', brands);
    
    // Get price range
    const minPrice = document.querySelector('.price-input.min')?.value;
    const maxPrice = document.querySelector('.price-input.max')?.value;
    if (minPrice) url.searchParams.set('min_price', minPrice);
    if (maxPrice) url.searchParams.set('max_price', maxPrice);
    
    // Get stock status
    const stockRadio = document.querySelector('input[name="stock"]:checked');
    if (stockRadio && stockRadio.value) url.searchParams.set('stock', stockRadio.value);
    
    window.location.href = url.toString();
}

function clearFilters() {
    const url = new URL(window.location.href);
    url.searchParams.delete('category');
    url.searchParams.delete('type');
    url.searchParams.delete('brand');
    url.searchParams.delete('min_price');
    url.searchParams.delete('max_price');
    url.searchParams.delete('stock');
    url.searchParams.delete('search');
    
    // Uncheck all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('input[name="stock"]').forEach(radio => radio.checked = radio.value === '');
    
    window.location.href = url.toString();
}

function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

function removeFilterValue(filterName, value) {
    const url = new URL(window.location.href);
    const currentValue = url.searchParams.get(filterName);
    if (currentValue) {
        const values = currentValue.split(',').filter(v => v != value);
        if (values.length > 0) {
            url.searchParams.set(filterName, values.join(','));
        } else {
            url.searchParams.delete(filterName);
        }
    }
    window.location.href = url.toString();
}