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

// အကောင့်ဝင်ပြီးမှ သွားလို့ရမည့် စာမျက်နှာများ
Route::middleware('auth')->group(function () {
    Route::get('/home', function () { return view('home'); })->name('home');
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
    Route::resource('admin/types', TypeController::class)->names('admin.types');
});

// // မူလစာမျက်နှာ
// Route::get('/', function () {
//     return view('welcome');
// });
