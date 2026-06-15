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



// Customer
Route::middleware('auth')->group(function () {
    // Customer profile & order history
    Route::get('/profile', function () { return view('profile'); })->name('profile');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
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
