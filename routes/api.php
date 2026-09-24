<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Prefix mặc định của file này là: /api
| Nhóm version 1: /api/v1/...
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    // 1. Dữ liệu Trang chủ (Banners, Menu, Flash Sale, New Products)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 2. Sản phẩm (Tìm kiếm, Gợi ý, Lọc, So sánh, Chi tiết)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/suggest', [ProductController::class, 'suggest'])->name('products.suggest');
    Route::get('/products/compare', [ProductController::class, 'compare'])->name('products.compare');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

    // 3. Danh mục
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

    // 4. Thương hiệu
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

    // 5. Mã giảm giá
    Route::post('/coupons/apply', [OrderController::class, 'applyCoupon'])->name('coupons.apply');

    // 6. Xác thực & Tài khoản (Public Auth API)
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/check-phone', [AuthController::class, 'checkPhone'])->name('check_phone');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify_otp');
        Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend_otp');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
        
        // Yêu cầu Bearer Token
        Route::middleware('api.auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    // 7. Giỏ hàng (Cart API - Yêu cầu Bearer Token)
    Route::prefix('cart')->name('cart.')->middleware('api.auth')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    });

    // 8. Đơn hàng & Thanh toán (Orders API - Yêu cầu Bearer Token)
    Route::prefix('orders')->name('orders.')->middleware('api.auth')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    });

    // 9. Quản lý tài khoản cá nhân (Account API - Yêu cầu Bearer Token)
    Route::prefix('account')->name('account.')->middleware('api.auth')->group(function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::match(['post', 'put'], '/profile', [AccountController::class, 'updateProfile'])->name('update_profile');
    });

    // 10. Đánh giá sản phẩm (Reviews API)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->middleware('api.auth')->name('reviews.destroy');
});
