// Customer Layout JavaScript - Extracted from UI Prototypes

document.addEventListener('DOMContentLoaded', function() {
    // Cart Drawer Logic
    const cartIcon = document.getElementById('cartIconBtn');
    const cartDrawer = document.getElementById('cartDrawer');
    const cartOverlay = document.getElementById('cartOverlay');
    const closeCartBtn = document.getElementById('closeCartBtn');

    if (cartIcon && cartDrawer && cartOverlay) {
        const openCart = (e) => {
            e.preventDefault();
            cartDrawer.classList.add('open');
            cartOverlay.classList.add('open');
        };

        const closeCart = () => {
            cartDrawer.classList.remove('open');
            cartOverlay.classList.remove('open');
        };

        cartIcon.addEventListener('click', openCart);
        
        if (closeCartBtn) {
            closeCartBtn.addEventListener('click', closeCart);
        }
        
        cartOverlay.addEventListener('click', closeCart);
    }

    // Add to cart buttons
    const addBtns = document.querySelectorAll('.btn-add');
    addBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (cartDrawer && cartOverlay) {
                cartDrawer.classList.add('open');
                cartOverlay.classList.add('open');
            }
        });
    });

    // Quantity buttons in cart drawer
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const action = this.dataset.action;
            const variantId = this.dataset.variantId;
            const quantitySpan = this.parentElement.querySelector('.item-quantity');
            let quantity = parseInt(quantitySpan.textContent);

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            // Update display
            quantitySpan.textContent = quantity;

            // Update cart via AJAX (you'll need to implement this endpoint)
            // For now, just update display
            // You can add AJAX call here to update session
        });
    });

    // Profile Dropdown Logic
    window.toggleProfileDropdown = function() {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    };

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profileDropdown');
        const profileButton = document.querySelector('.profile-dropdown button');
        
        if (dropdown && profileButton) {
            if (!dropdown.contains(e.target) && !profileButton.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        }
    });

    // Add to wishlist function
    window.addToWishlist = function(itemId) {
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

    // Add to cart function for shop page
    window.addToCart = function(itemId) {
        // Check if user is authenticated
        const isAuthenticated = document.querySelector('.profile-dropdown') !== null;
        
        if (!isAuthenticated) {
            alert('Please login to add items to cart');
            window.location.href = '/login';
            return;
        }

        // Redirect to product detail page to select variant
        window.location.href = `/shop/${itemId}`;
    };
});