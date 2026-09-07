<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích hoạt tài khoản - Smember</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #f4f6f8; display: flex; flex-direction: column; min-height: 100vh; }

        /* HEADER */
        .auth-header { background: #fff; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 40px; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; }
        .logo-area { display: flex; align-items: center; gap: 15px; text-decoration: none; color: #333; }
        .logo-area img { height: 40px; }
        .logo-text { font-size: 20px; font-weight: 700; color: #d70018; }
        .header-title { font-size: 16px; color: #666; font-weight: 500; }
        .help-link { color: #d70018; text-decoration: none; font-size: 14px; font-weight: 500; }

        /* OTP CONTAINER */
        .auth-wrapper { flex: 1; display: flex; align-items: flex-start; justify-content: center; padding-bottom: 60px; }
        .otp-container { 
            background: #fff; 
            margin-top: 20px;
            padding: 0 40px 40px 40px; 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            width: 100%; 
            max-width: 450px; 
            text-align: center; 
        }

        .otp-icon { 
            width: 250px; 
            
        }
        .otp-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; }
        .otp-desc { color: #666; font-size: 14px; line-height: 1.5; margin-bottom: 30px; }
        
        /* Hiệu ứng số đếm ngược màu đỏ */
        .otp-desc b { color: #d70018; font-weight: 700; transition: color 0.3s; }
        .otp-desc b.expired { color: #999; }

        /* INPUTS */
        .otp-inputs { display: flex; justify-content: center; gap: 15px; margin-bottom: 30px; }
        .otp-box { width: 60px; height: 60px; border: 2px solid #eee; border-radius: 12px; text-align: center; font-size: 26px; font-weight: 700; color: #d70018; outline: none; transition: all 0.3s ease; background: #fff; cursor: text; }
        .otp-box:focus { border-color: #d70018; box-shadow: 0 0 0 4px rgba(215, 0, 24, 0.1); transform: translateY(-2px); }
        .otp-box[readonly] { background: #fafafa; color: #333; }

        /* BUTTONS */
        .btn-otp-confirm { width: 100%; padding: 16px; border: none; border-radius: 12px; background: #d70018; color: #fff; font-weight: 600; font-size: 16px; cursor: pointer; opacity: 0.5; pointer-events: none; transition: 0.3s; }
        .btn-otp-confirm.active { opacity: 1; pointer-events: auto; box-shadow: 0 5px 15px rgba(215, 0, 24, 0.3); }
        .btn-otp-confirm:active { transform: scale(0.98); }

        .resend-section { margin-top: 30px; padding-top: 20px; border-top: 1px dashed #eee; }
        .resend-options { display: flex; justify-content: center; gap: 20px; margin-bottom: 15px; }
        .radio-label { display: flex; align-items: center; gap: 8px; font-size: 14px; color: #555; cursor: pointer; }
        .radio-label input { accent-color: #d70018; width: 16px; height: 16px; }
        
        .btn-timer { background: transparent; color: #888; border: 1px solid #ddd; padding: 8px 20px; border-radius: 30px; font-size: 13px; font-weight: 500; cursor: not-allowed; transition: 0.3s; }
        .btn-timer.ready { background: #fff; color: #d70018; border-color: #d70018; cursor: pointer; }
        .btn-timer.ready:hover { background: #d70018; color: #fff; }

        /* NOTIFICATION */
        #toast-box { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .toast-item { background: #333; color: #fff; padding: 14px 24px; border-radius: 8px; margin-bottom: 10px; display: flex; align-items: center; gap: 12px; font-size: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); animation: slideIn 0.3s ease; }
        .toast-item.success { background: #06bc60; }
        .toast-item.error { background: #e02b2b; }
        
        .shake { animation: shake 0.5s; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); } 20%, 40%, 60%, 80% { transform: translateX(5px); } }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        @media (max-width: 600px) {
            .auth-header { padding: 10px 0; margin-bottom: 20px; }
            .otp-container { padding: 25px 20px; box-shadow: none; background: transparent; }
            .header-title, .help-link { display: none; }
            .otp-box { width: 50px; height: 50px; font-size: 20px; }
        }
    </style>
</head>
<body>
    <div id="toast-box"></div>

     <header class="register-header">
        <div class="header-content">
            <a href="index.html" class="header-logo">
                <img src="{{ asset('asset/img/logolaptoptfd.png') }}" alt="LaptopTF">
            </a>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="otp-container">
            <img class="otp-icon" src="{{ asset('asset/img_linh_vat_tf/rong_tf_like.png') }}" alt="Icon">
            
            <h2 class="otp-title">Nhập mã xác thực</h2>
            <p class="otp-desc">
                Mã xác thực đã được gửi về Email đăng ký.<br>
                Mã có hiệu lực trong vòng <b><span id="textTimer">60</span> giây</b>.
            </p>

            <div class="otp-inputs" id="otpForm">
                <input type="text" class="otp-box" maxlength="1" readonly>
                <input type="text" class="otp-box" maxlength="1" readonly>
                <input type="text" class="otp-box" maxlength="1" readonly>
                <input type="text" class="otp-box" maxlength="1" readonly>
            </div>

            <button class="btn-otp-confirm" id="btnConfirm">Xác nhận</button>

            <div class="resend-section">
                <p style="font-size: 13px; color: #888; margin-bottom: 15px;">Bạn chưa nhận được mã?</p>
                <div class="resend-options">
                    <label class="radio-label"><input type="radio" name="method" value="sms"> SMS</label>
                    <label class="radio-label"><input type="radio" name="method" value="email" checked> Email</label>
                </div>
                <button class="btn-timer" id="btnTimer">Gửi lại sau 60s</button>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('.otp-box');
    const btnConfirm = document.getElementById('btnConfirm');
    const btnTimer = document.getElementById('btnTimer');
    const textTimer = document.getElementById('textTimer'); // Lấy thẻ span trong text
    const otpContainer = document.querySelector('.otp-container');

    // --- 1. SETUP ĐẾM NGƯỢC ĐỒNG BỘ ---
    let countdownInterval;
    
    function startCountdown(seconds = 60) {
        const now = new Date().getTime();
        const endTime = localStorage.getItem('otp_end_time');
        let targetTime;

        if (endTime && new Date(endTime) > now && seconds === 60) {
            targetTime = new Date(endTime).getTime();
        } else {
            targetTime = now + (seconds * 1000);
            localStorage.setItem('otp_end_time', new Date(targetTime));
        }

        updateTimerDisplay(targetTime);
        clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            updateTimerDisplay(targetTime);
        }, 1000);
    }

    function updateTimerDisplay(targetTime) {
        const now = new Date().getTime();
        const distance = targetTime - now;
        const secondsLeft = Math.floor(distance / 1000);

        // Cập nhật text ở 2 nơi: trên dòng mô tả và dưới nút
        if (secondsLeft <= 0) {
            clearInterval(countdownInterval);
            localStorage.removeItem('otp_end_time');
            
            // Hết giờ
            btnTimer.innerText = "Gửi lại mã";
            btnTimer.disabled = false;
            btnTimer.classList.add('ready');
            
            if(textTimer) {
                textTimer.innerText = "0";
                textTimer.parentElement.classList.add('expired'); // Đổi màu xám
            }
        } else {
            // Còn giờ
            btnTimer.innerText = `Gửi lại sau ${secondsLeft}s`;
            btnTimer.disabled = true;
            btnTimer.classList.remove('ready');
            
            if(textTimer) {
                textTimer.innerText = secondsLeft;
                textTimer.parentElement.classList.remove('expired');
            }
        }
    }

    // Chạy ngay khi load
    startCountdown(60);

    // --- 2. XỬ LÝ NHẬP LIỆU (READONLY HACK) ---
    inputs.forEach((input, index) => {
        input.addEventListener('focus', () => {
            input.removeAttribute('readonly');
            input.select();
        });

        input.addEventListener('input', (e) => {
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (!val) { e.target.value = ''; return; }

            if (val.length === 1) {
                e.target.value = val;
                if (index < inputs.length - 1) inputs[index + 1].focus();
            } else if (val.length > 1) {
                val.split('').forEach((char, i) => {
                    if (inputs[index + i]) {
                        inputs[index + i].removeAttribute('readonly');
                        inputs[index + i].value = char;
                    }
                });
                e.target.value = val[0];
                let nextFocus = Math.min(index + val.length, inputs.length - 1);
                inputs[nextFocus].focus();
            }
            checkBtnState();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace') {
                if (input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                }
                setTimeout(checkBtnState, 0);
            }
            if (e.key === 'ArrowLeft' && index > 0) inputs[index - 1].focus();
            if (e.key === 'ArrowRight' && index < inputs.length - 1) inputs[index + 1].focus();
        });
    });

    function checkBtnState() {
        const otp = Array.from(inputs).map(i => i.value).join('');
        if (otp.length === 4) {
            btnConfirm.classList.add('active');
            btnConfirm.disabled = false;
        } else {
            btnConfirm.classList.remove('active');
            btnConfirm.disabled = true;
        }
    }

    // --- 3. NÚT XÁC NHẬN ---
    btnConfirm.addEventListener('click', async () => {
        const otpCode = Array.from(inputs).map(i => i.value).join('');
        if (otpCode.length !== 4) return;

        btnConfirm.innerText = "Đang kiểm tra...";
        btnConfirm.disabled = true;

        try {
            const res = await fetch('/verify-otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ otp: otpCode })
            });
            const data = await res.json();

            if (data.status === 'success') {
                showToast('Kích hoạt thành công!', 'success');
                setTimeout(() => window.location.href = data.redirect, 1000);
            } else {
                showToast(data.message, 'error');
                resetForm();
            }
        } catch (e) {
            showToast('Lỗi kết nối server!', 'error');
            resetForm();
        }
    });

    function resetForm() {
        otpContainer.classList.add('shake');
        setTimeout(() => otpContainer.classList.remove('shake'), 500);
        inputs.forEach(i => { i.value = ''; i.setAttribute('readonly', true); });
        inputs[0].focus();
        btnConfirm.innerText = "Xác nhận";
        btnConfirm.disabled = false;
        btnConfirm.classList.remove('active');
    }

    // --- 4. GỬI LẠI MÃ ---
    btnTimer.addEventListener('click', async () => {
        let type = 'email';
        if(document.querySelector('input[name="method"]:checked')) {
            type = document.querySelector('input[name="method"]:checked').value;
        }
        btnTimer.innerText = "Đang gửi...";
        try {
            const res = await fetch('/resend-otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ type })
            });
            const data = await res.json();
            if (data.status === 'success') {
                showToast(data.message, 'success');
                localStorage.removeItem('otp_end_time'); 
                startCountdown(60); 
                inputs.forEach(i => i.value = '');
                inputs[0].focus();
            } else {
                showToast(data.message, 'error');
                btnTimer.innerText = "Gửi lại mã";
            }
        } catch (e) {
            showToast('Lỗi hệ thống', 'error');
            btnTimer.innerText = "Gửi lại mã";
        }
    });

    function showToast(msg, type) {
        const box = document.getElementById('toast-box');
        if(!box) return;
        const el = document.createElement('div');
        el.className = `toast-item ${type}`;
        el.innerHTML = type === 'success' ? `<i class="ri-checkbox-circle-fill"></i> ${msg}` : `<i class="ri-error-warning-fill"></i> ${msg}`;
        box.appendChild(el);
        setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 500); }, 4000);
    }
});
</script>
</body>
</html>