<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập LaptopTF</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">
</head>
<body>
    <header class="register-header">
        <div class="header-content">
            <a href="index.html" class="header-logo">
                <img src="{{ asset('asset/img/logolaptoptfd.png') }}" alt="LaptopTF">
            </a>
        </div>
    </header>
    
    <div class="login-box">
        <h2 style="text-align: center; color: #d70018; margin-bottom: 25px;">Đăng nhập</h2>

        @if(session('success'))
            <div style="background: #e6f9f0; color: #06bc60; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                <i class="ri-checkbox-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('xuly.dangnhap') }}" method="POST" id="loginForm">
            @csrf
            
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">

            <div class="form-group @error('so_dien_thoai') error @enderror">
                <label>Số điện thoại</label>
                <input type="text" name="so_dien_thoai" id="so_dien_thoai" 
                       class="form-input" 
                       placeholder="Nhập số điện thoại" 
                       value="{{ old('so_dien_thoai') }}">
                
                <div class="text-danger" id="error-phone">
                    @error('so_dien_thoai') <i class="ri-error-warning-fill"></i> {{ $message }} @enderror
                </div>
            </div>

            <div class="form-group @error('mat_khau') error @enderror">
                <label>Mật khẩu</label>
                <input type="password" name="mat_khau" id="mat_khau" 
                       class="form-input" 
                       placeholder="Nhập mật khẩu">
                
                <div class="text-danger" id="error-pass">
                    @error('mat_khau') <i class="ri-error-warning-fill"></i> {{ $message }} @enderror
                </div>

                <a href="{{ route('password.forgot') }}" class="forgot-link">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">Đăng nhập ngay</button>
            
            <p style="text-align: center; font-size: 13px; margin-top: 20px;">
                Chưa có tài khoản? <a href="{{ route('dangky') }}" style="color: #d70018; font-weight: 600; text-decoration: none;">Đăng ký</a>
            </p>
        </form>
    </div>

    <script src="{{ asset('asset/js/login.js') }}"></script>
</body>
</html>