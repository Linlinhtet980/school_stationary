<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BundleController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController;

Route::controller(AuthController::class)->group(function () {
    
    // Guest (အကောင့်မဝင်ရသေးသူများ) အတွက်
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.post');
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register')->name('register.post');
    });

    // Auth (အကောင့်ဝင်ပြီးသူများ) အတွက်
    Route::middleware('auth')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});

// Default route - redirect based on auth status
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->isCustomer()) {
            return redirect()->route('home');
        } else {
            return redirect()->route('admin.dashboard');
        }
    }
    return redirect()->route('login');
})->name('default');



// Customer - Public access (guests can view)
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::post('/contact', 'submitContact')->name('contact.submit');
});

Route::controller(ShopController::class)->group(function () {
    Route::get('/shop', 'index')->name('shop.index');
    Route::get('/product/{id}', 'show')->name('shop.show');
    Route::get('/item/{id}/first-variant', 'getFirstVariant')->name('shop.first-variant');
    Route::get('/shop/filter', 'filter')->name('shop.filter');
    Route::get('/new-arrivals', 'newArrivals')->name('shop.new-arrivals');
    Route::get('/bestsellers', 'bestsellers')->name('shop.bestsellers');
    Route::get('/b2s-deals', 'b2sDeals')->name('shop.b2s-deals');
    Route::get('/search', 'search')->name('shop.search');
});

// Customer - Authentication required
Route::middleware('auth')->group(function () {
    // Cart routes
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add', 'add')->name('cart.add');
        Route::post('/cart/add/{item}', 'addByItem')->name('cart.add-item');
        Route::post('/cart/update', 'update')->name('cart.update');
        Route::post('/cart/update-ajax', 'updateAjax')->name('cart.update-ajax');
        Route::post('/cart/remove-ajax', 'removeAjax')->name('cart.remove-ajax');
        Route::post('/cart/remove', 'remove')->name('cart.remove');
        Route::delete('/cart/remove/{variantId}', 'removeByVariant')->name('cart.remove-variant');
        Route::post('/cart/clear', 'clear')->name('cart.clear');
        // AJAX endpoints
        Route::post('/cart/add-ajax', 'addAjax')->name('cart.add-ajax');
        Route::get('/cart/count-ajax', 'getCountAjax')->name('cart.count-ajax');
        Route::get('/cart/get-items', 'getItemsAjax')->name('cart.get-items');
        Route::post('/cart/add-bundle/{bundle}', 'addBundle')->name('cart.add-bundle');
    });

    // Checkout routes
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/checkout/success/{id}', 'success')->name('checkout.success');
        Route::post('/checkout/address', 'addAddress')->name('checkout.add-address');
        Route::post('/checkout/coupon', 'validateCoupon')->name('checkout.validate-coupon');
        Route::get('/checkout/stripe-success', [CheckoutController::class, 'stripeSuccess'])->name('checkout.stripe-success');
    });

    // Customer order routes
    Route::controller(CustomerOrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('profile.orders');
        Route::get('/orders/{id}', 'show')->name('profile.order-detail');
        Route::post('/orders/{id}/cancel', 'cancel')->name('profile.order-cancel');
    });

    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update', 'updateProfile')->name('profile.update');
        Route::post('/profile/image', 'updateImage')->name('profile.update-image');
        Route::post('/profile/password', 'changePassword')->name('profile.change-password');
        
        // Address routes
        Route::post('/profile/address', 'addAddress')->name('profile.add-address');
        Route::post('/profile/address/{id}', 'updateAddress')->name('profile.update-address');
        Route::delete('/profile/address/{id}', 'deleteAddress')->name('profile.delete-address');
        Route::post('/profile/address/{id}/default', 'setDefaultAddress')->name('profile.set-default-address');
        
        // Wishlist routes
        Route::get('/wishlist', 'wishlist')->name('profile.wishlist');
        Route::post('/wishlist/add', 'addToWishlist')->name('profile.add-wishlist');
        Route::delete('/wishlist/{id}', 'removeFromWishlist')->name('profile.remove-wishlist');
    });
});

// admin/staff
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/notifications/mark-all-read', [DashboardController::class, 'markAllRead'])->name('admin.notifications.markAllRead');
    
    // Orders - All staff can view/update
    Route::resource('admin/orders', OrderController::class)->only(['index', 'show', 'update'])->names('admin.orders');
    Route::post('admin/orders/{order}/payment/verify', [OrderController::class, 'verifyPayment'])->name('admin.orders.verifyPayment');
    
    // Customers - All staff can view
    Route::resource('admin/customers', CustomerController::class)->only(['index', 'show'])->names('admin.customers');
    Route::post('admin/customers/{customer}/block', [CustomerController::class, 'block'])->name('admin.customers.block');
    
    // Reviews - All staff can manage
    Route::resource('admin/reviews', ReviewController::class)->only(['index', 'update', 'destroy'])->names('admin.reviews');
});

// Inventory Management (Role 1 - Super Admin, Role 2 - Inventory Manager)
Route::middleware(['auth', 'admin:super_admin,inventory_manager'])->group(function () {
    Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
    Route::resource('admin/types', TypeController::class)->names('admin.types');
    Route::resource('admin/brands', BrandController::class)->names('admin.brands');
    Route::resource('admin/items', ItemController::class)->names('admin.items');
    Route::resource('admin/banners', BannerController::class)->names('admin.banners');
    Route::delete('admin/items/image/{id}', [ItemController::class, 'destroyImage'])->name('admin.items.destroyImage');
});

// Bundles (Role 1 - Super Admin Only)
Route::middleware(['auth', 'admin:super_admin'])->group(function () {
    Route::resource('admin/bundles', BundleController::class)->names('admin.bundles');
});

// Staff Management (Role 1 - Super Admin Only)
Route::middleware(['auth', 'admin:super_admin'])->group(function () {
    Route::resource('admin/staff', StaffController::class)->names('admin.staff');
});

// // မူလစာမျက်နှာ
// Route::get('/', function () {
//     return view('welcome');
// });
