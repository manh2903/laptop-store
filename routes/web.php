<?php

use Illuminate\Support\Facades\Route;

// --- 1. IMPORT CONTROLLERS CLIENT ---
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\CategoryController as ClientCategoryController; 

// --- 2. IMPORT CONTROLLERS CHUNG ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

// --- 3. IMPORT CONTROLLERS ADMIN ---
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController; 
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Client\ReviewController;


/*
|--------------------------------------------------------------------------
| A. KHU VỰC PUBLIC (Khách vãng lai xem được)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham/{slug}', [ProductDetailController::class, 'show'])->name('client.product.detail');
Route::get('/so-sanh', [ProductDetailController::class, 'compare'])->name('product.compare');
Route::get('/danh-muc/{slug}', [ClientCategoryController::class, 'show'])->name('product.category');

// XÓA BỎ NHÓM GIỎ HÀNG Ở ĐÂY (VÌ ĐÃ CHUYỂN VÀO NHÓM AUTH BÊN DƯỚI)

Route::post('/reviews', [ReviewController::class, 'store'])->name('client.reviews.store');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('client.reviews.destroy')->middleware('auth');


/*
|--------------------------------------------------------------------------
| B. XÁC THỰC NGƯỜI DÙNG
|--------------------------------------------------------------------------
*/
Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('dangky');
Route::post('/dang-ky', [AuthController::class, 'register'])->name('xuly.dangky');
Route::post('/check-phone', [AuthController::class, 'checkPhoneAvailability'])->name('api.check_phone');
Route::get('/kich-hoat-tai-khoan', [AuthController::class, 'showOtpForm'])->name('otp.view');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login'])->name('xuly.dangnhap');
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
Route::get('/quen-mat-khau', [AuthController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/quen-mat-khau', [AuthController::class, 'sendResetOtp'])->name('password.send_otp');
Route::get('/xac-thuc-lay-mat-khau', [AuthController::class, 'showResetOtpForm'])->name('otp.reset.view');
Route::post('/xac-thuc-lay-mat-khau', [AuthController::class, 'verifyResetOtp'])->name('otp.reset.verify');
Route::post('/otp-reset/resend', [AuthController::class, 'resendResetOtp'])->name('otp.reset.resend');
Route::get('/doi-mat-khau-moi', [AuthController::class, 'showChangePasswordForm'])->name('password.change.view');
Route::post('/doi-mat-khau-moi', [AuthController::class, 'updatePassword'])->name('password.change.update');

/*
|--------------------------------------------------------------------------
| C. KHU VỰC THÀNH VIÊN (PHẢI ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/tai-khoan', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/api/send-upgrade-email', [AuthController::class, 'sendUpgradeEmail']);

    // NHÓM GIỎ HÀNG CHUẨN - KHÔNG THAM SỐ URL
    Route::prefix('gio-hang')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index'); 
        Route::post('/them', [CartController::class, 'addToCart'])->name('add');
        Route::post('/cap-nhat', [CartController::class, 'updateQuantity'])->name('update'); 
        Route::post('/xoa', [CartController::class, 'removeItem'])->name('remove'); 
    });

    // 2. Quản lý Đơn hàng (Thanh toán, Lịch sử mua hàng, Chi tiết đơn hàng)
Route::prefix('don-hang')->name('order.')->group(function () {
    Route::get('/thanh-toan', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/dat-hang', [OrderController::class, 'store'])->name('store');
    Route::get('/lich-su', [OrderController::class, 'index'])->name('index');
    Route::get('/chi-tiet/{ma_don_hang}', [OrderController::class, 'show'])->name('show');
   // Thêm dòng này vào
   Route::post('/mua-ngay', [OrderController::class, 'buyNow'])->name('buyNow');
   
});
});

/*
|--------------------------------------------------------------------------
| D. KHU VỰC ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('auth.login');
        Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register'])->name('register.post');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::prefix('categories')->name('categories.')->group(function () {
            Route::post('/update-status', [CategoryController::class, 'updateStatus'])->name('update-status');
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/store', [CategoryController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::post('brands/update-status', [BrandController::class, 'updateStatus'])->name('brands.update-status');
        Route::resource('brands', BrandController::class);

        Route::post('products/update-status', [AdminProductController::class, 'updateStatus'])->name('products.update-status');
        Route::get('products/duplicate/{id}', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
        Route::resource('products', AdminProductController::class);

        Route::get('carts', [AdminCartController::class, 'index'])->name('carts.index');
        Route::get('carts/{id}', [AdminCartController::class, 'show'])->name('carts.show');
        Route::get('orders', function() { return "Chức năng Đơn hàng đang xây dựng"; })->name('orders.index');
        
        Route::delete('/products/delete-image/{id}', [AdminProductController::class, 'deleteImage']);
        Route::delete('/products/delete-video/{id}', [AdminProductController::class, 'deleteVideo'])->name('products.deleteVideo');
        Route::delete('products/delete-variant/{id}', [AdminProductController::class, 'deleteVariant'])->name('products.delete_variant');
        Route::delete('products/delete-config/{id}', [AdminProductController::class, 'deleteConfig'])->name('products.delete_config');
    });
});