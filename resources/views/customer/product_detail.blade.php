<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco-Friendly Notebook - Campus Supply</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/home.css"> <!-- Related Products အတွက် -->
    <link rel="stylesheet" href="css/product.css"> <!-- Product Specific CSS -->
</head>
<body>

    <!-- NAVBAR (Copy from master.html) -->
    <nav class="navbar"> ... </nav>

    <!-- MAIN CONTENT -->
    <main class="main-wrapper">
        
        <!-- Breadcrumb -->
        <div style="max-width: 1200px; margin: 2rem auto; padding: 0 2rem; color: #A0AEC0; font-size: 0.9rem;">
            <a href="index.html" style="color:var(--primary); text-decoration:none;">Home</a> / 
            <a href="shop.html" style="color:var(--primary); text-decoration:none;">Shop</a> / 
            Eco-Friendly A5 Notebook
        </div>

        <!-- Product Detail Section -->
        <div class="product-detail-container">
            
            <!-- LEFT: Image Gallery -->
            <div class="gallery-wrapper">
                <div class="main-image-container">
                    <img src="https://images.unsplash.com/photo-1531346878377-a5f20ce31158?w=500" id="mainImage" class="main-image" alt="Product">
                </div>
                <div class="thumbnail-list">
                    <!-- data-src တွင် ပုံအကြီး Link ထည့်ပါ -->
                    <div class="thumbnail active" data-src="https://images.unsplash.com/photo-1531346878377-a5f20ce31158?w=500">
                        <img src="https://images.unsplash.com/photo-1531346878377-a5f20ce31158?w=150" alt="Thumb 1">
                    </div>
                    <div class="thumbnail" data-src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?w=500">
                        <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?w=150" alt="Thumb 2">
                    </div>
                </div>
            </div>

            <!-- RIGHT: Product Info -->
            <div class="product-info">
                <div class="product-brand">EcoStationery</div>
                <h1 class="product-title">Eco-Friendly A5 Notebook</h1>
                
                <div class="product-price-box">
                    <span class="product-price">8,500 Ks</span>
                    <span class="product-old-price">10,000 Ks</span>
                </div>

                <div class="stars" style="color: var(--primary); font-size: 1rem;">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                    <span style="color: #A0AEC0; font-size: 0.9rem; margin-left: 0.5rem;">(124 Reviews)</span>
                </div>

                <p class="product-short-desc">
                    Premium quality, 100% recycled paper notebook. Perfect for students and professionals. Features 160 dotted pages, an expandable inner pocket, and an elastic closure.
                </p>

                <!-- Color Variants -->
                <div class="variants">
                    <div class="variant-title">Color: Navy Blue</div>
                    <div class="color-options">
                        <div class="color-btn active" style="background-color: #1a365d;"></div>
                        <div class="color-btn" style="background-color: #b91c1c;"></div>
                        <div class="color-btn" style="background-color: #047857;"></div>
                    </div>
                </div>

                <!-- Add to Cart Action -->
                <div class="action-row">
                    <div class="qty-selector">
                        <button type="button" class="qty-btn" id="btnMinus">-</button>
                        <input type="text" class="qty-input" id="qtyInput" value="1" readonly>
                        <button type="button" class="qty-btn" id="btnPlus">+</button>
                    </div>
                    <button class="btn-add-large">
                        <i class="fa-solid fa-cart-shopping"></i> ADD TO CART
                    </button>
                </div>
                
                <div style="color: #047857; font-weight: 600; font-size: 0.9rem; margin-top: 1rem;">
                    <i class="fa-solid fa-check-circle"></i> In Stock - Ready to ship
                </div>
            </div>
        </div>

        <!-- TABS SECTION -->
        <div class="tabs-container">
            <div class="tab-headers">
                <button class="tab-btn active" data-target="descTab">Description</button>
                <button class="tab-btn" data-target="specTab">Specifications</button>
                <button class="tab-btn" data-target="reviewTab">Reviews (124)</button>
            </div>
            
            <div class="tab-content active" id="descTab">
                <h3>Designed for Creators</h3>
                <p>Our Eco-Friendly A5 Notebook is crafted with sustainability in mind. The pages are made from 100% post-consumer waste, providing a smooth writing experience without bleeding through. Whether you are bullet journaling, taking lecture notes, or sketching, this notebook is your perfect companion.</p>
            </div>
            
            <div class="tab-content" id="specTab">
                <ul style="list-style-position: inside; margin-top: 1rem;">
                    <li><strong>Size:</strong> A5 (145 x 210 mm)</li>
                    <li><strong>Pages:</strong> 160 pages (80 sheets)</li>
                    <li><strong>Paper Weight:</strong> 100 gsm</li>
                    <li><strong>Binding:</strong> Thread-bound opens flat</li>
                </ul>
            </div>

            <div class="tab-content" id="reviewTab">
                <p>No reviews yet. Be the first to review this product!</p>
            </div>
        </div>

    </main>

    <!-- FOOTER & CART DRAWER (Copy from master.html) -->
    <footer class="footer"> ... </footer>
    <div class="cart-overlay" id="cartOverlay"></div>
    <div class="cart-drawer" id="cartDrawer"> ... </div>

    <!-- External JS -->
    <script src="{{ asset('js/layouts/customer.js') }}"></script>
    <script src="{{ asset('js/customer/product_detail.js') }}"></script> <!-- Product Specific JS -->
</body>
</html>