<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Campus Supply</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="{{ asset('css/layouts/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/shop.css') }}"> <!-- Shop Specific CSS -->
</head>
<body>

    <!-- NAVBAR (Copy from master.html) -->
    <nav class="navbar"> ... </nav>

    <!-- MAIN CONTENT -->
    <main class="main-wrapper">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">SHOP ALL PRODUCTS</h1>
            <div class="breadcrumb">
                <a href="index.html">Home</a> / Shop
            </div>
        </div>

        <!-- Shop Container (Grid Layout) -->
        <div class="shop-container">
            
            <!-- LEFT: Sidebar Filters -->
            <aside class="sidebar">
                <!-- Category Filter -->
                <div class="filter-group">
                    <h3 class="filter-title">Categories</h3>
                    <ul class="filter-list">
                        <li><label class="custom-checkbox"><input type="checkbox" checked> All Products</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Notebooks & Paper</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Pens & Pencils</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Art Supplies</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Backpacks</label></li>
                    </ul>
                </div>

                <!-- Price Filter -->
                <div class="filter-group">
                    <h3 class="filter-title">Price Range</h3>
                    <div class="price-range-wrapper">
                        <input type="range" id="priceSlider" class="price-slider" min="1000" max="50000" step="1000" value="25000">
                        <div class="price-values">
                            <span>1,000 Ks</span>
                            <span id="priceOutput" class="text-primary-price">25,000 Ks</span>
                        </div>
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="filter-group">
                    <h3 class="filter-title">Brands</h3>
                    <ul class="filter-list">
                        <li><label class="custom-checkbox"><input type="checkbox"> Moleskine</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Faber-Castell</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Parker</label></li>
                        <li><label class="custom-checkbox"><input type="checkbox"> Lamy</label></li>
                    </ul>
                </div>
            </aside>

            <!-- RIGHT: Main Product Area -->
            <div class="shop-main">
                
                <!-- Topbar (Sort & Results) -->
                <div class="shop-topbar">
                    <div class="results-count">Showing 1-9 of 36 results</div>
                    <select class="sort-select">
                        <option value="default">Default Sorting</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                    </select>
                </div>

                <!-- Product Grid -->
                <div class="shop-grid">
                    <!-- Product Card 1 -->
                    <div class="card">
                        <a href="product-detail.html" class="card-img-link">
                            <img src="https://images.unsplash.com/photo-1531346878377-a5f20ce31158?w=300" class="card-img" alt="Notebook">
                        </a>
                        <div class="card-title">Eco-Friendly A5 Notebook</div>
                        <div class="card-desc">Navy/Yellow</div>
                        <div class="card-price-row">
                            <div class="card-price">8,500 Ks</div>
                        </div>
                        <button class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
                    </div>
                    
                    <!-- Product Card 2 -->
                    <div class="card">
                        <div class="badge-new">SALE</div>
                        <a href="product-detail.html" class="card-img-link">
                            <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?w=300" class="card-img" alt="Pens">
                        </a>
                        <div class="card-title">Premium Gel Pens Set</div>
                        <div class="card-desc">Pack of 12</div>
                        <div class="card-price-row">
                            <div class="card-price">12,000 Ks</div>
                        </div>
                        <button class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="card">
                        <a href="product-detail.html" class="card-img-link">
                            <img src="https://images.unsplash.com/photo-1503694978374-8a2fa686963a?w=300" class="card-img" alt="Backpack">
                        </a>
                        <div class="card-title">Canvas Student Backpack</div>
                        <div class="card-desc">Water Resistant</div>
                        <div class="card-price-row">
                            <div class="card-price">45,000 Ks</div>
                        </div>
                        <button class="btn-add"><span>Add to Cart</span> <i class="fa-solid fa-cart-shopping"></i></button>
                    </div>
                    
                    <!-- (လိုအပ်ပါက Card များကို ထပ်ပွားထည့်နိုင်ပါသည်) -->
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    <a href="#" class="page-btn"><i class="fa-solid fa-angle-left"></i></a>
                    <a href="#" class="page-btn active">1</a>
                    <a href="#" class="page-btn">2</a>
                    <a href="#" class="page-btn">3</a>
                    <a href="#" class="page-btn"><i class="fa-solid fa-angle-right"></i></a>
                </div>

            </div>
        </div>

    </main>

    <!-- FOOTER & CART DRAWER (Copy from master.html) -->
    <footer class="footer"> ... </footer>
    <div class="cart-overlay" id="cartOverlay"></div>
    <div class="cart-drawer" id="cartDrawer"> ... </div>

    <!-- External JS -->
    <script src="{{ asset('js/layouts/customer.js') }}"></script>
    <script src="{{ asset('js/customer/shop.js') }}"></script> <!-- Shop Specific JS -->
</body>
</html>