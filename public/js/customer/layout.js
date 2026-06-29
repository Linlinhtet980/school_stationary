
document.addEventListener('DOMContentLoaded', function () {
    // Mobile Sidebar Logic
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenuBtn = document.getElementById('closeMobileMenuBtn');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');

    function toggleMobileMenu() {
        mobileSidebar.classList.toggle('active');
        mobileOverlay.classList.toggle('active');
    }

    if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleMobileMenu);
    if (closeMobileMenuBtn) closeMobileMenuBtn.addEventListener('click', toggleMobileMenu);
    if (mobileOverlay) mobileOverlay.addEventListener('click', toggleMobileMenu);

    // Profile Dropdown Logic
    window.toggleProfileDropdown = function () {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('profileDropdown');
        const profileButton = document.querySelector('.profile-dropdown button');

        if (dropdown && profileButton) {
            if (!dropdown.contains(e.target) && !profileButton.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        }
    });

    // Add to wishlist function
    window.addToWishlist = function (itemId) {
        // Check if user is authenticated
        const isAuthenticated = document.body.classList.contains('authenticated');

        if (!isAuthenticated) {
            alert('Please login to add items to wishlist');
            window.location.href = '/login';
            return;
        }

        // Create form data
        const formData = new FormData();
        formData.append('item_id', itemId);

        // Send AJAX request to add to wishlist
        fetch('/profile/wishlist/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: formData,
            credentials: 'same-origin'
        })
            .then(response => response.text())
            .then(data => {
                // Try to parse as HTML response
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');

                // Check for success message
                const successAlert = doc.querySelector('.alert-success');
                const errorAlert = doc.querySelector('.alert-error');

                if (successAlert) {
                    alert('Item added to wishlist successfully!');
                } else if (errorAlert) {
                    alert(errorAlert.textContent || 'Failed to add to wishlist');
                } else {
                    alert('Item added to wishlist!');
                }
            })
            .catch(error => {
                console.error('Error adding to wishlist:', error);
                alert('An error occurred. Please try again.');
            });
    };

    // Add to cart function for shop page (AJAX version)
    window.addToCart = async function (itemId) {
        // Check if user is authenticated
        const isAuthenticated = document.body.classList.contains('authenticated');

        if (!isAuthenticated) {
            alert('Please login to add items to cart');
            window.location.href = '/login';
            return;
        }

        // Get the first available variant and default quantity
        try {
            const response = await fetch(`/item/${itemId}/first-variant`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error('Could not get product variant');
            }

            const data = await response.json();

            if (!data.variant_id) {
                alert('This product is not available');
                return;
            }

            // Add to cart via AJAX
            await addToCartAjax(data.variant_id, 1);

        } catch (error) {
            console.error('Error:', error);
            // Fallback to product detail page if AJAX fails
            window.location.href = `/product/${itemId}`;
        }
    };

    // Add to cart via AJAX (with specific variant and quantity)
    window.addToCartAjax = async function (variantId, quantity = 1) {
        try {
            const response = await fetch('/cart/add-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    variant_id: variantId,
                    quantity: quantity
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Could not add item to cart');
            }

            const data = await response.json();

            // Update cart badge
            updateCartBadge(data.cart_count);

            // Show success notification
            showNotification(data.message, 'success');

            // Open cart drawer
            toggleCartDrawer(true);
            loadCartData();

            return data;

        } catch (error) {
            console.error('Error adding to cart:', error);
            showNotification(error.message, 'error');
            return null;
        }
    };

    // Update cart badge
    function updateCartBadge(newCount) {
        const cartBadges = document.querySelectorAll('.cart-badge');
        cartBadges.forEach(cartBadge => {
            cartBadge.textContent = newCount;
            // Add animation
            cartBadge.classList.add('badge-animation');
            setTimeout(() => {
                cartBadge.classList.remove('badge-animation');
            }, 1000);
        });
    }

    // Show notification
    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to body
        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('notification-hidden');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Cart drawer functionality
    function toggleCartDrawer(show = null) {
        const cartDrawer = document.getElementById('cartDrawer');
        const cartOverlay = document.getElementById('cartDrawerOverlay');

        if (show === null) {
            // Toggle current state
            cartDrawer.classList.toggle('show');
            cartOverlay.classList.toggle('show');
        } else if (show) {
            cartDrawer.classList.add('show');
            cartOverlay.classList.add('show');
        } else {
            cartDrawer.classList.remove('show');
            cartOverlay.classList.remove('show');
        }
    }

    // Cart drawer event listeners
    const cartIconBtn = document.getElementById('cartIconBtn');
    const closeCartBtn = document.getElementById('closeCartBtn');
    const cartOverlay = document.getElementById('cartDrawerOverlay');

    // Open cart drawer
    if (cartIconBtn) {
        cartIconBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleCartDrawer(true);
            loadCartData();
        });
    }

    // Close cart drawer
    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', function () {
            toggleCartDrawer(false);
        });
    }

    // Close cart drawer on overlay click
    if (cartOverlay) {
        cartOverlay.addEventListener('click', function () {
            toggleCartDrawer(false);
        });
    }

    // Load cart data via AJAX
    async function loadCartData() {
        try {
            const response = await fetch('/cart/get-items', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load cart');
            }

            const data = await response.json();
            renderCartItems(data.items);
            updateCartDrawerTotal(data.total);

        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }

    // Render cart items in drawer
    function renderCartItems(items) {
        const cartDrawerBody = document.getElementById('cartDrawerBody');
        const cartDrawerEmpty = document.getElementById('cartDrawerEmpty');
        const cartDrawerFooter = document.getElementById('cartDrawerFooter');

        if (!items || items.length === 0) {
            cartDrawerBody.innerHTML = '';
            cartDrawerBody.appendChild(cartDrawerEmpty.cloneNode(true));
            cartDrawerFooter.style.display = 'none';
            return;
        }

        cartDrawerFooter.style.display = 'block';
        cartDrawerBody.innerHTML = '';

        items.forEach(item => {
            const cartItemHTML = `
                <div class="cart-drawer-item" data-variant-id="${item.variant_id}">
                    <img src="${item.image}" alt="${item.name}">
                    <div class="cart-drawer-item-info">
                        <div class="cart-drawer-item-name">${item.name}</div>
                        <div class="cart-drawer-item-variant">${item.variant_name}</div>
                        <div class="cart-drawer-item-bottom" style="display:flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                            <div class="cart-drawer-item-quantity" style="margin-top: 0;">
                                <button class="cart-drawer-qty-btn" onclick="window.updateCartItemQuantity('${item.key}', ${item.quantity - 1})">-</button>
                                <span>${item.quantity}</span>
                                <button class="cart-drawer-qty-btn" onclick="window.updateCartItemQuantity('${item.key}', ${item.quantity + 1})">+</button>
                            </div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="cart-drawer-item-price">Ks ${numberFormat(item.price * item.quantity)}</div>
                                <button class="cart-drawer-item-remove" style="margin-top:0;" onclick="window.removeCartItem('${item.key}')" title="Remove Item"><i class="fa-solid fa-trash-can"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            cartDrawerBody.innerHTML += cartItemHTML;
        });
    }

    // Update cart drawer total
    function updateCartDrawerTotal(total) {
        const cartDrawerTotal = document.getElementById('cartDrawerTotal');
        if (cartDrawerTotal) {
            cartDrawerTotal.textContent = `Ks ${numberFormat(total)}`;
        }
    }

    // Number format helper
    function numberFormat(num) {
        return new Intl.NumberFormat().format(num);
    }

    // Expose functions globally for inline onclick handlers
    window.updateCartItemQuantity = async function (key, quantity) {
        if (quantity < 1) return;

        try {
            const response = await fetch('/cart/update-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    key: key,
                    quantity: quantity
                })
            });

            if (!response.ok) {
                throw new Error('Failed to update quantity');
            }

            const data = await response.json();
            loadCartData();
            updateCartBadge(data.cart_count);

        } catch (error) {
            console.error('Error updating quantity:', error);
            showNotification('Failed to update quantity', 'error');
        }
    }

    window.removeCartItem = async function (key) {
        try {
            const response = await fetch('/cart/remove-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    key: key
                })
            });

            if (!response.ok) {
                throw new Error('Failed to remove item');
            }

            const data = await response.json();
            loadCartData();
            updateCartBadge(data.cart_count);

        } catch (error) {
            console.error('Error removing item:', error);
            showNotification('Failed to remove item', 'error');
        }
    }

    // Live Search Autocomplete Functionality
    const headerSearchInput = document.getElementById('headerSearchInput');
    const searchLiveResults = document.getElementById('searchLiveResults');
    let searchTimeout = null;

    if (headerSearchInput && searchLiveResults) {
        headerSearchInput.addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                searchLiveResults.classList.remove('show');
                searchLiveResults.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/search?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(items => {
                        if (!items || items.length === 0) {
                            searchLiveResults.innerHTML = `
                            <div style="padding: 12px 16px; font-size: 0.85rem; color: #888; text-align: center;">
                                No products found for "${query}"
                            </div>
                        `;
                        } else {
                            searchLiveResults.innerHTML = items.map(item => `
                            <a href="${item.url}" class="search-live-item">
                                <img src="${item.image || '/images/no-image.png'}" alt="${item.name}">
                                <div class="search-live-item-info">
                                    <div class="search-live-item-name">${item.name}</div>
                                    <div class="search-live-item-price">${item.price}</div>
                                </div>
                            </a>
                        `).join('');
                        }
                        searchLiveResults.classList.add('show');
                    })
                    .catch(error => {
                        console.error('Error in live search:', error);
                    });
            }, 250);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!headerSearchInput.contains(e.target) && !searchLiveResults.contains(e.target)) {
                searchLiveResults.classList.remove('show');
            }
        });

        headerSearchInput.addEventListener('focus', function () {
            if (this.value.trim().length >= 2 && searchLiveResults.children.length > 0) {
                searchLiveResults.classList.add('show');
            }
        });
    }
});