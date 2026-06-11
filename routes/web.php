<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;

// Customer
Route::middleware('auth')->group(function () {
    // Customer profile & order history
    Route::get('/profile', function () { return view('profile'); })->name('profile');
    
    Route::get('/home', function () { return view('login'); })->name('home');
});

// admin/staff
Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
    Route::resource('admin/types', TypeController::class)->names('admin.types');
    Route::resource('admin/brands', BrandController::class)->names('admin.brands');
    Route::resource('admin/items', ItemController::class)->names('admin.items');
    Route::resource('admin/banners', BannerController::class)->names('admin.banners');
    Route::resource('admin/staff', StaffController::class)->names('admin.staff');
    Route::delete('admin/items/image/{id}', [ItemController::class, 'destroyImage'])->name('admin.items.destroyImage');
    
    Route::resource('admin/orders', OrderController::class)->only(['index', 'show', 'update'])->names('admin.orders');
    Route::resource('admin/customers', CustomerController::class)->only(['index', 'show'])->names('admin.customers');
    Route::post('admin/customers/{customer}/block', [CustomerController::class, 'block'])->name('admin.customers.block');
});

// // မူလစာမျက်နှာ
// Route::get('/', function () {
//     return view('welcome');
// });
