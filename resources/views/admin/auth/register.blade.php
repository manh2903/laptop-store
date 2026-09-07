@extends('admin.auth.layout')

@section('title', 'Đăng ký Quản trị viên')

@section('content')

{{-- CSS MÀU ĐỎ (Red Theme) - Giống trang Login --}}
<style>
    .login-btn {
        background: #dc2626 !important;
        border: none !important;
        transition: all 0.3s ease;
        font-weight: 600 !important;
    }
    .login-btn:hover {
        background: #b91c1c !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
    }
    .form-group input:focus {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }
    .form-group:focus-within i {
        color: #dc2626 !important;
    }
    a { color: #dc2626 !important; transition: 0.2s; }
    a:hover { color: #991b1b !important; text-decoration: underline; }

    .brand-header { text-align: center; margin-bottom: 25px; }
    .logo-icon {
        width: 50px; height: 50px; background: #fee2e2; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;
    }
    .logo-icon i { font-size: 24px; color: #dc2626; }
    .brand-title { font-size: 20px; font-weight: 800; color: #1f2937; text-transform: uppercase; }
    
    .alert-box { padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
    .alert-error { background-color: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; }
</style>

<div class="login-box" style="width: 400px; padding: 30px;">
    
    {{-- LOGO --}}
    <div class="brand-header">
        <div class="logo-icon"><i class="fas fa-user-plus"></i></div>
        <h1 class="brand-title">Tạo tài khoản Admin</h1>
    </div>

    {{-- BÁO LỖI --}}
    @if ($errors->any())
        <div class="alert-box alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>- {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('admin.register.post') }}" method="POST">
        @csrf
        
        {{-- Họ tên --}}
        <div class="form-group">
            <i class="fas fa-user"></i>
            <input type="text" name="fullname" placeholder="Họ và tên" required value="{{ old('fullname') }}">
        </div>

        {{-- Số điện thoại --}}
        <div class="form-group">
            <i class="fas fa-phone"></i>
            <input type="text" name="phone" placeholder="Số điện thoại" required value="{{ old('phone') }}">
        </div>

        {{-- Email --}}
        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email đăng nhập" required value="{{ old('email') }}">
        </div>

        {{-- Mật khẩu --}}
        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Mật khẩu (Tối thiểu 6 ký tự)" required>
        </div>

        {{-- Nhập lại Mật khẩu --}}
        <div class="form-group">
            <i class="fas fa-check-circle"></i>
            <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
        </div>

        <button type="submit" class="login-btn">
            ĐĂNG KÝ TÀI KHOẢN
        </button>
    </form>

    <div class="register-prompt-box">
        Đã có tài khoản?
        <a href="{{ route('admin.login') }}" style="font-weight: bold;">Đăng nhập ngay</a>
    </div>
</div>
@endsection