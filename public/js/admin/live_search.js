document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('.search-form input[name="search"]');
    let debounceTimer;

    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value;
            const form = this.closest('form');
            const url = new URL(form.action);
            
            // Preserve current URL parameters (like sort, status, stock, page)
            const currentParams = new URLSearchParams(window.location.search);
            currentParams.forEach((value, key) => {
                url.searchParams.set(key, value);
            });
            
            // Update search parameter
            url.searchParams.set('search', query);
            
            // Reset to page 1 on new search
            url.searchParams.set('page', 1);

            debounceTimer = setTimeout(() => {
                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newContainer = doc.getElementById('tableDataContainer');
                    const currentContainer = document.getElementById('tableDataContainer');
                    
                    if (newContainer && currentContainer) {
                        currentContainer.innerHTML = newContainer.innerHTML;
                        
                        // Update the browser URL without reloading
                        window.history.pushState({}, '', url.toString());
                    }
                })
                .catch(error => console.error('Error fetching search results:', error));
            }, 400); // 400ms debounce
        });
        
        // Prevent form submission on enter
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
            });
        }
    });

    // Dropdown toggle logic
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.dropdown-toggle');
        const isDropdownMenu = e.target.closest('.custom-dropdown-menu');

        if (toggleBtn) {
            e.preventDefault();
            const dropdown = toggleBtn.closest('.custom-dropdown');
            dropdown.classList.toggle('open');
        } else if (!isDropdownMenu) {
            // Close all dropdowns if click is outside
            document.querySelectorAll('.custom-dropdown.open').forEach(dropdown => {
                dropdown.classList.remove('open');
            });
        }
    });
});

// Function to apply filters via AJAX when a radio/checkbox changes
window.applyFilters = function() {
    const form = document.querySelector('.live-search-form');
    if (!form) return;

    const url = new URL(form.action);
    const formData = new FormData(form);

    // Keep existing URL parameters
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.forEach((value, key) => {
        if (!formData.has(key)) {
            url.searchParams.set(key, value);
        }
    });

    // Apply new filter parameters
    for (let [key, value] of formData.entries()) {
        if (value) {
            url.searchParams.set(key, value);
        }
    }

    url.searchParams.set('page', 1);

    fetch(url.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        const newContainer = doc.getElementById('tableDataContainer');
        const currentContainer = document.getElementById('tableDataContainer');
        
        if (newContainer && currentContainer) {
            currentContainer.innerHTML = newContainer.innerHTML;
            window.history.pushState({}, '', url.toString());
        }
    })
    .catch(error => console.error('Error fetching filter results:', error));
};

// =====================================================
// Filter Dropdown Toggle (.filter-toggle / .filter-dropdown)
// Used by: Bundles, Reviews pages
// =====================================================
document.addEventListener('DOMContentLoaded', function() {

    // Toggle open/close
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.filter-toggle');
        const isInsideMenu = e.target.closest('.filter-menu');

        if (toggleBtn) {
            e.preventDefault();
            const wrapper = toggleBtn.closest('.filter-dropdown');
            if (wrapper) wrapper.classList.toggle('open');
        } else if (!isInsideMenu) {
            // Close all filter dropdowns if clicking outside
            document.querySelectorAll('.filter-dropdown.open').forEach(d => d.classList.remove('open'));
        }
    });

    // Reset filter button: clear all radios to default then submit
    document.querySelectorAll('.reset-filter').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('.filter-form');
            if (!form) return;
            // Reset radios to first option in each group
            const groups = {};
            form.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (!groups[radio.name]) {
                    groups[radio.name] = radio;
                    radio.checked = true;
                } else {
                    radio.checked = false;
                }
            });
            // Clear search field in URL
            const url = new URL(window.location.href);
            url.searchParams.delete('sort');
            url.searchParams.delete('status');
            url.searchParams.delete('stock');
            url.searchParams.delete('search');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    });

    // Auto-submit filter form on radio change (AJAX)
    document.querySelectorAll('.filter-form input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const form = this.closest('.filter-form');
            if (!form) return;

            const url = new URL(form.action || window.location.href);

            // Apply all current radio values
            form.querySelectorAll('input[type="radio"]:checked').forEach(r => {
                url.searchParams.set(r.name, r.value);
            });

            // Preserve search
            const searchInput = document.querySelector('.live-search-form input[name="search"]');
            if (searchInput && searchInput.value) {
                url.searchParams.set('search', searchInput.value);
            }

            url.searchParams.set('page', 1);

            fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('tableDataContainer');
                const currentContainer = document.getElementById('tableDataContainer');
                if (newContainer && currentContainer) {
                    currentContainer.innerHTML = newContainer.innerHTML;
                    window.history.pushState({}, '', url.toString());
                }
                // Close dropdown after applying
                document.querySelectorAll('.filter-dropdown.open').forEach(d => d.classList.remove('open'));
            })
            .catch(err => console.error('Filter error:', err));
        });
    });
});

