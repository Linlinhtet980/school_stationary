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
});