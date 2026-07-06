<aside class="sidebar" id="sidebarFilter">
    <div class="sidebar-header-mobile">
        <h3>Filters</h3>
        <button type="button" class="close-sidebar-btn" onclick="toggleMobileSidebar()"><i
                class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="{{ $action }}" method="GET" id="filterForm">
        
        <!-- Accordion Category/Type -->
        <div class="accordion-item filter-group">
            <div class="accordion-header filter-title" onclick="toggleAccordion(this)">
                <span><i class="fa-solid fa-list-ul" style="margin-right: 8px; color: var(--primary);"></i>Categories</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-content">
                <ul class="filter-list">
                    <li>
                        <label class="custom-radio">
                            <input type="radio" name="type" value="" {{ !request('type') ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit();">
                            All Categories
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
                                <ul class="type-list sub-accordion-content">
                                    @foreach($category->types as $type)
                                        <li>
                                            <label class="custom-radio">
                                                <input type="radio" name="type" value="{{ $type->id }}" {{ request('type') == $type->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                                                {{ $type->name }}
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
        <div class="accordion-item filter-group">
            <div class="accordion-header filter-title" onclick="toggleAccordion(this)">
                <span><i class="fa-solid fa-tags" style="margin-right: 8px; color: var(--primary);"></i>Brands</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-content">
                <input type="text" id="brandSearch" class="brand-search-input" placeholder="Search brands..." onkeyup="filterBrands()">
                <ul class="filter-list" id="brandList">
                    <li class="brand-item">
                        <label class="custom-radio">
                            <input type="radio" name="brand" value="" {{ !request('brand') ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit();">
                            <span class="brand-name">All Brands</span>
                        </label>
                    </li>
                    @foreach($brands as $brand)
                        <li class="brand-item">
                            <label class="custom-radio">
                                <input type="radio" name="brand" value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit();">
                                <span class="brand-name">{{ $brand->name }}</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Accordion Price -->
        <div class="accordion-item filter-group">
            <div class="accordion-header filter-title" onclick="toggleAccordion(this)">
                <span><i class="fa-solid fa-wallet" style="margin-right: 8px; color: var(--primary);"></i>Price Range</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="accordion-content">
                <div class="filter-price">
                    <input type="number" name="min_price" placeholder="Min Ks" value="{{ request('min_price') }}">
                    <span>-</span>
                    <input type="number" name="max_price" placeholder="Max Ks" value="{{ request('max_price') }}">
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
</script>
