<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
    <style>
        .forgot-box { 
            max-width: 400px;
            margin: 100px auto; 
            background: #fff; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            text-align: center; 
        }

        .btn-reset { 
            width: 100%; 
            padding: 12px; 
            background: #d70018; 
            color: #fff; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
            margin-top: 20px; 
        }
    </style>
</head>
<body>
    <header class="register-header">
        <div class="header-content">
            <a href="index.html" class="header-logo">
                <img src="{{ asset('asset/img/logolaptoptfd.png') }}" alt="LaptopTF">
            </a>
        </div>
    </header>
    <div class="forgot-box">
        <h2 style="color: #d70018;">Quên mật khẩu?</h2>
        <p style="font-size: 13px; color: #666; margin: 10px 0 25px;">Nhập số điện thoại của bạn để nhận mã khôi phục.</p>
        
        <form action="{{ route('password.send_otp') }}" method="POST">
            @csrf
            <div style="text-align: left;">
                <label style="font-size: 14px; font-weight: 600;">Số điện thoại</label>
                <input 
    <input 
    type="text" 
    name="so_dien_thoai" 
    class="form-input" 
    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;" 
    placeholder="Ví dụ: 0xxx.xxx.xxx" 
    maxlength="10" 
    inputmode="numeric" 
    pattern="0\d{9}"
    title="Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0"
    required 
    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
>
            </div>
            @error('so_dien_thoai') <p style="color:red; font-size:12px; text-align:left;">{{ $message }}</p> @enderror
            
            <button type="submit" class="btn-reset">Gửi mã OTP</button>
            <a href="{{ route('login') }}" style="display: block; margin-top: 15px; font-size: 13px; color: #666; text-decoration: none;">Quay lại đăng nhập</a>
        </form>
    </div>
</body>
</html>