<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực OTP - Quên mật khẩu</title>
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    <style>
        .login-box { margin-top: 80px; }
        /* CSS cho nút Gửi lại */
        .resend-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .resend-link a {
            color: #d70018;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }
        .resend-link a.disabled {
            color: #999;
            pointer-events: none; /* Khóa không cho bấm */
            cursor: default;
        }
        
        /* CSS thông báo Toast */
       /* --- CSS TOAST XẾP CHỒNG (CARD STACK) --- */
        #toast-container { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            width: 320px; /* Cần cố định chiều rộng để xếp chồng chuẩn */
            pointer-events: none; /* Để bấm xuyên qua vùng trống */
        }
        
        .toast { 
            position: absolute; /* Quan trọng: Để các thẻ đè lên nhau */
            top: 0; 
            right: 0; 
            width: 100%; 
            padding: 15px 20px; 
            border-radius: 8px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
            display: flex; 
            align-items: center; 
            gap: 12px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            
            /* Hiệu ứng chuyển động mượt mà */
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 0;
            transform: translateX(100%);
            pointer-events: auto; /* Cho phép bấm vào toast */
        }
        
        .toast.success { background: #4caf50; }
        .toast.error { background: #d70018; }
        .toast i { font-size: 18px; }
    </style>
</head>
<body>
    <div id="toast-container"></div>

    <header class="register-header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="header-logo">
                <img src="{{ asset('asset/img/logolaptoptfd.png') }}" alt="LaptopTF">
            </a>
        </div>
    </header>

    <div class="login-box">
        <h2 style="text-align: center; color: #d70018;">Nhập mã xác thực</h2>
        <p style="text-align: center; font-size: 13px; margin-bottom: 20px;">
            Mã OTP đã được gửi đến Email của bạn.
        </p>

                <form id="verifyForm" onsubmit="handleVerify(event)">
            @csrf
            <div class="form-group">
               <input 
    type="text" 
    name="otp" 
    class="form-input" 
    placeholder="Nhập mã 4 số" 
    maxlength="4" 
    inputmode="numeric" 
    pattern="\d*"
    required 
    style="text-align: center; letter-spacing: 5px; font-weight: bold; font-size: 18px;"
    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);"
>
                
            <button type="submit" id="btnVerify" class="btn-login">Xác nhận</button>
        </form>

        <div class="resend-link">
            Bạn chưa nhận được mã? <br>
            <a id="btnResend" onclick="resendOtp()">Gửi lại mã (<span id="countdown">60</span>s)</a>
        </div>
    </div>

    <script>
    // --- KHAI BÁO BIẾN ---
    const countdownEl = document.getElementById('countdown');
    const btnResend = document.getElementById('btnResend');
    let timerId;
    const STORAGE_KEY = 'otp_timer_end'; // Khóa lưu thời gian
    let toasts = []; // Mảng quản lý thông báo xếp chồng

    // ============================================================
    // 1. QUẢN LÝ THÔNG BÁO XẾP CHỒNG (CARD STACK EFFECT)
    // ============================================================
    function showToast(msg, type) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<i class="ri-${type === 'success' ? 'checkbox-circle' : 'error-warning'}-fill"></i> <span>${msg}</span>`;
        
        // Thêm vào HTML
        container.appendChild(toast);
        
        // Thêm vào đầu danh sách quản lý
        toasts.unshift(toast); 

        // Tính toán vị trí xếp chồng
        updateToastPositions();

        // Tự xóa sau 3 giây
        setTimeout(() => removeToast(toast), 3000);
    }

    function updateToastPositions() {
        toasts.forEach((toast, index) => {
            // Chỉ hiện tối đa 3 cái
            if (index >= 3) {
                toast.style.opacity = '0';
                toast.style.pointerEvents = 'none';
                return;
            }

            // Thuật toán xếp chồng: Cái sau thấp hơn và nhỏ hơn cái trước
            const offset = index * 15; 
            const scale = 1 - (index * 0.05); 
            
            toast.style.transform = `translateY(${offset}px) scale(${scale})`;
            toast.style.opacity = '1';
            toast.style.zIndex = 100 - index;
        });
    }

    function removeToast(toast) {
        const index = toasts.indexOf(toast);
        if (index > -1) {
            toasts.splice(index, 1); // Xóa khỏi mảng
            
            // Hiệu ứng bay lên trời khi xóa
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-30px) scale(0.8)';
            
            // Xóa hẳn khỏi HTML sau 0.3s
            setTimeout(() => toast.remove(), 300);
            
            // Cập nhật lại vị trí các cái còn lại
            updateToastPositions();
        }
    }

    // ============================================================
    // 2. CÁC HÀM ĐẾM NGƯỢC (TIMER - KHÔNG RESET KHI F5)
    // ============================================================
    function initTimer() {
        let endTime = localStorage.getItem(STORAGE_KEY);
        let now = Date.now();

        // Nếu chưa có timer hoặc đã hết hạn từ trước
        if (!endTime || now > endTime) {
            handleExpired();
            return;
        }
        // Nếu vẫn còn thời gian -> Chạy tiếp
        runCountdown(endTime);
    }

    function runCountdown(endTime) {
        btnResend.classList.add('disabled'); // Khóa nút
        
        clearInterval(timerId);
        timerId = setInterval(() => {
            const now = Date.now();
            const distance = endTime - now;
            const secondsLeft = Math.floor(distance / 1000);

            // [SỬA LỖI TẠI ĐÂY] 
            // Luôn tìm lại thẻ span countdown mới nhất trên màn hình
            const currentCountdownEl = document.getElementById('countdown');

            if (secondsLeft < 0) {
                handleExpired();
            } else {
                // Chỉ cập nhật nếu tìm thấy thẻ
                if (currentCountdownEl) {
                    currentCountdownEl.innerText = secondsLeft;
                }
            }
        }, 1000);
    }

    function handleExpired() {
        clearInterval(timerId);
        // KHÔNG XÓA localStorage để tránh lỗi reset khi F5
        btnResend.classList.remove('disabled'); // Mở nút
        btnResend.innerHTML = 'Gửi lại mã ngay';
    }

    // ============================================================
    // 3. HÀM GỬI LẠI MÃ (RESEND OTP)
    // ============================================================
    async function resendOtp() {
        btnResend.classList.add('disabled');
        btnResend.innerText = "Đang gửi...";

        try {
            const response = await fetch("{{ route('otp.reset.resend') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            });
            const data = await response.json();

            if (data.status === 'success') {
                showToast(data.message, 'success');
                
                // Lưu mốc thời gian mới: Hiện tại + 60s
                const newEndTime = Date.now() + 60000;
                localStorage.setItem(STORAGE_KEY, newEndTime);
                
                countdownEl.innerText = 60;
                btnResend.innerHTML = `Gửi lại mã (<span id="countdown">60</span>s)`;
                runCountdown(newEndTime);
            } else {
                showToast(data.message, 'error');
                handleExpired();
            }
        } catch (error) {
            console.error(error);
            showToast("Lỗi kết nối", 'error');
            handleExpired();
        }
    }

    // ============================================================
    // 4. HÀM XÁC THỰC MÃ (AJAX - KHÔNG LOAD LẠI TRANG)
    // ============================================================
    async function handleVerify(e) {
        e.preventDefault(); // Chặn load lại trang

        const btnVerify = document.getElementById('btnVerify');
        const form = document.getElementById('verifyForm');
        const formData = new FormData(form);

        // Hiệu ứng đang xử lý
        const oldText = btnVerify.innerText;
        btnVerify.innerText = "Đang kiểm tra...";
        btnVerify.style.opacity = "0.7";
        btnVerify.disabled = true;

        try {
            const response = await fetch("{{ route('otp.reset.verify') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                }
            });
            
            const data = await response.json();

            if (data.status === 'success') {
                showToast("Xác thực thành công! Đang chuyển hướng...", 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                // Sai mã -> Báo lỗi
                showToast(data.message, 'error');
                
                // Reset nút bấm về trạng thái cũ
                btnVerify.innerText = oldText;
                btnVerify.style.opacity = "1";
                btnVerify.disabled = false;
            }
        } catch (error) {
            console.error(error);
            showToast("Lỗi hệ thống", 'error');
            btnVerify.innerText = oldText;
            btnVerify.style.opacity = "1";
            btnVerify.disabled = false;
        }
    }

    // ============================================================
    // 5. KHỞI CHẠY KHI VÀO TRANG
    // ============================================================
    if(localStorage.getItem(STORAGE_KEY)) {
        initTimer();
    } else {
        // Lần đầu vào trang -> Set 60s
        const firstTimeEnd = Date.now() + 60000;
        localStorage.setItem(STORAGE_KEY, firstTimeEnd);
        runCountdown(firstTimeEnd);
    }
</script>
</body>
</html>