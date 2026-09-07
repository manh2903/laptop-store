<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu - LaptopTF</title>
    <link rel="icon" href="{{ asset('asset/img/logotf.png') }}" type="image/png"/>
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .login-box { margin-top: 80px; }
        .error-text {
            color: #d70018;
            font-size: 12px;
            margin-top: 5px;
            display: none;
            font-weight: 500;
        }
        input.error-border { border: 1px solid #d70018 !important; }
        input.success-border { border: 1px solid #28a745 !important; }

        /* --- 2. CSS CHO NÚT ẨN/HIỆN MẬT KHẨU --- */
        .password-wrapper {
            position: relative; /* Để icon căn theo khung này */
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%); /* Căn giữa dọc */
            cursor: pointer;
            color: #666;
            z-index: 10;
        }
        .toggle-password:hover {
            color: #d70018;
        }
    </style>
</head>
<body>
    <header class="register-header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="header-logo">
                <img src="{{ asset('asset/img/logolaptoptfd.png') }}" alt="LaptopTF">
            </a>
        </div>
    </header>

    <div class="login-box">
        <h2 style="text-align: center; color: #d70018;">Đặt lại mật khẩu</h2>
        <p style="text-align: center; font-size: 13px; margin-bottom: 20px;">
            Hãy nhập mật khẩu mới cho tài khoản: <b>{{ session('phone') }}</b>
        </p>

        <form action="{{ route('password.change.update') }}" method="POST" id="reset-pass-form">
            @csrf
            
            <div class="form-group">
                <label>Mật khẩu mới</label>
                <div class="password-wrapper">
                    <input type="password" name="mat_khau_moi" id="mat_khau_moi" class="form-input" placeholder="Mật khẩu mới (Có chữ và số)" required>
                    <span class="toggle-password" onclick="togglePassword('mat_khau_moi', this)">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                
                <div id="error-msg-pass" class="error-text"></div>
                @error('mat_khau_moi') 
                    <div style="color: red; font-size: 12px; margin-top: 5px;">{{ $message }}</div> 
                @enderror
            </div>

            <div class="form-group">
                <label>Nhập lại mật khẩu</label>
                <div class="password-wrapper">
                    <input type="password" name="nhap_lai_mat_khau" id="nhap_lai_mat_khau" class="form-input" placeholder="Xác nhận mật khẩu mới" required>
                    <span class="toggle-password" onclick="togglePassword('nhap_lai_mat_khau', this)">
                        <i class="fa-regular fa-eye"></i>
                    </span>
                </div>
                
                <div id="error-msg-confirm" class="error-text"></div>
                @error('nhap_lai_mat_khau') 
                    <div style="color: red; font-size: 12px; margin-top: 5px;">{{ $message }}</div> 
                @enderror
            </div>

            <button type="submit" class="btn-login" id="btn-submit">Đổi mật khẩu & Đăng nhập</button>
        </form>
    </div>

    <script>
        // --- 3. HÀM ẨN/HIỆN MẬT KHẨU ---
        function togglePassword(inputId, el) {
            const input = document.getElementById(inputId);
            const icon = el.querySelector('i');

            if (input.type === "password") {
                input.type = "text"; // Hiện mật khẩu
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash'); // Đổi icon thành mắt gạch chéo
            } else {
                input.type = "password"; // Ẩn mật khẩu
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye'); // Đổi lại icon mắt mở
            }
        }

        // --- VALIDATE DỮ LIỆU (Code cũ) ---
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('reset-pass-form');
            const passInput = document.getElementById('mat_khau_moi');
            const confirmInput = document.getElementById('nhap_lai_mat_khau');
            const errorPass = document.getElementById('error-msg-pass');
            const errorConfirm = document.getElementById('error-msg-confirm');

            function checkPasswordStrength(password) {
                if (password.length < 7) return "Mật khẩu phải có hơn 6 ký tự.";
                if (!/[a-zA-Z]/.test(password)) return "Phải chứa ít nhất 1 chữ cái.";
                if (!/[0-9]/.test(password)) return "Phải chứa ít nhất 1 số.";
                return ""; 
            }

            passInput.addEventListener('input', function() {
                const error = checkPasswordStrength(this.value);
                if (error) {
                    errorPass.innerText = error;
                    errorPass.style.display = 'block';
                    this.classList.add('error-border');
                    this.classList.remove('success-border');
                } else {
                    errorPass.style.display = 'none';
                    this.classList.remove('error-border');
                    this.classList.add('success-border');
                }
                if(confirmInput.value) confirmInput.dispatchEvent(new Event('input'));
            });

            confirmInput.addEventListener('input', function() {
                if (this.value !== passInput.value) {
                    errorConfirm.innerText = "Mật khẩu xác nhận chưa khớp.";
                    errorConfirm.style.display = 'block';
                    this.classList.add('error-border');
                    this.classList.remove('success-border');
                } else {
                    errorConfirm.style.display = 'none';
                    this.classList.remove('error-border');
                    this.classList.add('success-border');
                }
            });

            form.addEventListener('submit', function(e) {
                const passError = checkPasswordStrength(passInput.value);
                const confirmError = (confirmInput.value !== passInput.value);

                if (passError || confirmError) {
                    e.preventDefault();
                    alert("Vui lòng nhập mật khẩu hợp lệ (Hơn 6 ký tự, có cả chữ và số)!");
                    if(passError) passInput.focus();
                    else confirmInput.focus();
                }
            });
        });
    </script>
</body>
</html>