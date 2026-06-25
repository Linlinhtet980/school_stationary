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

    // Real-time search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.trim();
                if (searchTerm.length >= 2) {
                    applyFilters();
                } else if (searchTerm.length === 0) {
                    applyFilters(); // Clear search
                }
            }, 300); // Debounce for 300ms
        });
    }

    // Real-time filter changes
    const filterCheckboxes = document.querySelectorAll('.category-checkbox, .type-checkbox, .brand-checkbox');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            applyFilters();
        });
    });

    // Real-time price range changes
    const priceInputs = document.querySelectorAll('.price-input');
    priceInputs.forEach(input => {
        input.addEventListener('change', function() {
            applyFilters();
        });
    });

    // Real-time stock filter changes
    const stockRadios = document.querySelectorAll('input[name="stock"]');
    stockRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            applyFilters();
        });
    });

});

// Filter Functions with AJAX
async function applyFilters() {
    try {
        // Get all filter values
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox:checked');
        const categories = Array.from(categoryCheckboxes).map(cb => cb.value).join(',');
        
        const typeCheckboxes = document.querySelectorAll('.type-checkbox:checked');
        const types = Array.from(typeCheckboxes).map(cb => cb.value).join(',');
        
        const brandCheckboxes = document.querySelectorAll('.brand-checkbox:checked');
        const brands = Array.from(brandCheckboxes).map(cb => cb.value).join(',');
        
        const minPrice = document.querySelector('.price-input.min')?.value;
        const maxPrice = document.querySelector('.price-input.max')?.value;
        
        const stockRadio = document.querySelector('input[name="stock"]:checked');
        const stock = stockRadio && stockRadio.value ? stockRadio.value : null;
        
        const searchInput = document.getElementById('searchInput');
        const search = searchInput ? searchInput.value.trim() : null;
        
        const sortSelect = document.getElementById('sortSelect');
        const sort = sortSelect ? sortSelect.value : 'latest';
        
        // Build filter parameters
        const params = new URLSearchParams();
        if (categories) params.append('category', categories);
        if (types) params.append('type', types);
        if (brands) params.append('brand', brands);
        if (minPrice) params.append('min_price', minPrice);
        if (maxPrice) params.append('max_price', maxPrice);
        if (stock) params.append('stock', stock);
        if (search) params.append('search', search);
        if (sort) params.append('sort', sort);
        
        // Update URL without reloading
        const url = new URL(window.location.href);
        url.search = params.toString();
        window.history.replaceState({}, '', url.toString());
        
        // Show loading state
        const productGrid = document.querySelector('.product-grid');
        if (productGrid) {
            productGrid.style.opacity = '0.5';
        }
        
        // Fetch filtered products
        const response = await fetch(`/shop/filter?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to filter products');
        }
        
        const data = await response.json();
        
        // Update product grid
        if (productGrid) {
            productGrid.innerHTML = data.html;
            productGrid.style.opacity = '1';
        }
        
        // Update pagination
        const pagination = document.querySelector('.pagination');
        if (pagination && data.pagination) {
            pagination.innerHTML = data.pagination;
        }
        
    } catch (error) {
        console.error('Error filtering products:', error);
        
        // Fallback to page reload on error
        const url = new URL(window.location.href);
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox:checked');
        const categories = Array.from(categoryCheckboxes).map(cb => cb.value).join(',');
        if (categories) url.searchParams.set('category', categories);
        window.location.href = url.toString();
    }
}

// Original fallback filter function (for non-Ajax scenarios)
function applyFiltersFallback() {
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