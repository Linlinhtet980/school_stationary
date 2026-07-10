<aside class="sidebar" id="sidebarFilter">
    <div class="sidebar-header-mobile">
        <h3>Filters</h3>
        <button type="button" class="close-sidebar-btn" onclick="toggleMobileSidebar()"><i
                class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="{{ $action }}" method="GET" id="filterForm">
        
        <!-- Accordion Category/Type -->
        <div class="accordion-item filter-group {{ request('type') || request('category') || empty(request()->except('page', 'sort')) ? 'active' : '' }}">
            <div class="accordion-header filter-title" onclick="toggleAccordion(this)">
                <span><i class="fa-solid fa-list-ul" style="margin-right: 8px; color: var(--primary);"></i>Categories</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-content">
                <ul class="filter-list">
                    <li style="margin-bottom: 12px;">
                        <label class="visual-pill {{ !request('type') ? 'active' : '' }}">
                            <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit();">
                            <span>All Categories</span>
                        </label>
                    </li>
                    @foreach($categories as $category)
                        <li class="category-tree-node">
                            <div class="category-name-header" onclick="toggleCategoryNode(this)">
                                <span><i class="fa-regular fa-folder" style="margin-right: 6px; color: var(--primary);"></i>{{ $category->name }} ({{ $category->types->count() }})</span>
                                @if($category->types->count() > 0)
                                    <i class="fa-solid fa-chevron-down"></i>
                                @endif
                            </div>
                            @if($category->types->count() > 0)
                                <ul class="pill-list sub-accordion-content" style="margin-top: 8px;">
                                    @foreach($category->types as $type)
                                        <li>
                                            <label class="visual-pill {{ request('type') == $type->id ? 'active' : '' }}">
                                                <input type="radio" name="type" value="{{ $type->id }}" {{ request('type') == $type->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                                                <span>{{ $type->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Accordion Brand -->
        <div class="accordion-item filter-group {{ request('brand') ? 'active' : '' }}">
            <div class="accordion-header filter-title" onclick="toggleAccordion(this)">
                <span><i class="fa-solid fa-tags" style="margin-right: 8px; color: var(--primary);"></i>Brands</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-content">
                <input type="text" id="brandSearch" class="brand-search-input" placeholder="Search brands..." onkeyup="filterBrands()">
                <ul class="pill-list" id="brandList">
                    <li class="brand-item">
                        <label class="visual-pill {{ !request('brand') ? 'active' : '' }}">
                            <input type="radio" name="brand" value="" {{ !request('brand') ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit();">
                            <span class="brand-name">All Brands</span>
                        </label>
                    </li>
                    @foreach($brands as $brand)
                        <li class="brand-item">
                            <label class="visual-pill {{ request('brand') == $brand->id ? 'active' : '' }}">
                                <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                                <span class="brand-name">{{ $brand->name }}</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Accordion Price -->
        <div class="accordion-item filter-group {{ request('min_price') || request('max_price') ? 'active' : '' }}">
            <div class="accordion-header filter-title" onclick="toggleAccordion(this)">
                <span><i class="fa-solid fa-wallet" style="margin-right: 8px; color: var(--primary);"></i>Price Range</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-content">
                <div class="price-slider-container">
                    <div class="price-display">
                        <span id="minPriceDisplay">{{ request('min_price', 0) }} Ks</span>
                        <span id="maxPriceDisplay">{{ request('max_price', 100000) }} Ks</span>
                    </div>
                    <div class="range-slider">
                        <div class="progress" id="sliderProgress"></div>
                        <input type="range" name="min_price" id="minPriceInput" min="0" max="100000" value="{{ request('min_price', 0) }}" step="1000" oninput="updateSlider()">
                        <input type="range" name="max_price" id="maxPriceInput" min="0" max="100000" value="{{ request('max_price', 100000) }}" step="1000" oninput="updateSlider()">
                    </div>
                </div>
                <button type="submit" class="btn-apply-filter">Apply Filter</button>
            </div>
        </div>
        
        @if(request('type') || request('category') || request('brand') || request('min_price') || request('max_price'))
            <a href="{{ $action }}" class="btn-clear-filters" style="margin-top: 1rem;">Clear All Filters</a>
        @endif
    </form>
</aside>

<script>
function toggleAccordion(element) {
    const item = element.parentElement;
    item.classList.toggle('active');
}

function toggleCategoryNode(element) {
    const item = element.parentElement;
    item.classList.toggle('active');
}

function filterBrands() {
    const input = document.getElementById('brandSearch');
    const filter = input.value.toLowerCase();
    const ul = document.getElementById('brandList');
    const li = ul.getElementsByClassName('brand-item');
    
    for (let i = 0; i < li.length; i++) {
        const span = li[i].querySelector('.brand-name');
        if (span) {
            const txtValue = span.textContent || span.innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
}

function updateSlider() {
    let minPrice = parseInt(document.getElementById('minPriceInput').value);
    let maxPrice = parseInt(document.getElementById('maxPriceInput').value);
    const maxAllowed = parseInt(document.getElementById('maxPriceInput').max);

    if (minPrice > maxPrice) {
        let temp = minPrice;
        minPrice = maxPrice;
        maxPrice = temp;
    }

    document.getElementById('minPriceDisplay').innerText = minPrice + ' Ks';
    document.getElementById('maxPriceDisplay').innerText = maxPrice + ' Ks';

    const progress = document.getElementById('sliderProgress');
    progress.style.left = (minPrice / maxAllowed) * 100 + '%';
    progress.style.right = 100 - (maxPrice / maxAllowed) * 100 + '%';
}

// Initialize slider on load
document.addEventListener('DOMContentLoaded', function() {
    updateSlider();
});
</script>
