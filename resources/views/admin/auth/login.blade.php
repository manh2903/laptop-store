@extends('admin.auth.layout')

@section('title', 'Đăng nhập Quản trị')

@section('content')
{{-- CSS tùy chỉnh riêng cho trang này để đổi sang màu ĐỎ --}}
<style>
    /* Đổi màu nút đăng nhập sang đỏ */
    .login-btn {
        background: #e63946 !important; /* Màu đỏ tươi */
        border: none !important;
        transition: 0.3s;
    }
    .login-btn:hover {
        background: #b91c1c !important; /* Màu đỏ đậm khi di chuột */
    }

    /* Đổi màu đường viền input khi bấm vào */
    .form-group input:focus {
        border-color: #e63946 !important;
    }
    .form-group i {
        color: #999; /* Màu icon mặc định */
    }
    .form-group input:focus + i, 
    .form-group:focus-within i {
        color: #e63946 !important; /* Icon đổi đỏ khi nhập liệu */
    }

    /* Đổi màu link */
    a {
        color: #e63946 !important;
    }
    a:hover {
        text-decoration: underline;
    }

    /* Style cho Logo Laptop */
    .brand-logo {
        text-align: center;
        margin-bottom: 25px;
    }
    .brand-logo i {
        font-size: 45px;
        color: #e63946;
        margin-bottom: 10px;
    }
    .brand-logo h1 {
        font-size: 24px;
        font-weight: 800;
        color: #333;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Hộp thông báo lỗi */
    .alert-error {
        background-color: #fee2e2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<div class="login-box">
    
    {{-- 1. LOGO LAPTOP MỚI --}}
    <div class="brand-logo">
        <i class="fas fa-laptop-code"></i> {{-- Icon Laptop --}}
        <h1>Laptop Store</h1>
        <p style="font-size: 13px; color: #666; font-weight: normal; margin-top: 5px;">Hệ thống quản trị bán hàng</p>
    </div>
    
    {{-- 2. HIỂN THỊ THÔNG BÁO LỖI (Nếu có) --}}
    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif
    @if (session('success'))
        <div class="alert-error" style="background-color: #d1fae5; border-color: #a7f3d0; color: #047857;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- 3. FORM ĐĂNG NHẬP --}}
    <form action="{{ route('admin.auth.login') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email quản trị viên" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Mật khẩu" required>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0 30px; font-size: 14px;">
            <label style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                <input type="checkbox" name="remember" style="width: auto; margin: 0;"> 
                Ghi nhớ tôi
            </label>
            <a href="{{ route('password.forgot') }}">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Đăng nhập hệ thống
        </button>
    </form>

    <div class="register-prompt-box">
        Bạn chưa có tài khoản?
        <a href="{{ route('admin.register') }}" class="register-link-footer" style="font-weight: bold;">Đăng ký Admin mới</a>
    </div>
</div>
@endsection