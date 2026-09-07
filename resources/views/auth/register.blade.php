<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên TFmember</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
    <link rel="stylesheet" href="{{ asset('asset/css/register.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_red.css">
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
    
    <div class="auth-container">
        <div class="auth-header">
            <h1 class="auth-title">Đăng ký trở thành TFMEMBER</h1>
            <div class="auth-mascot">
                <img src="{{ asset('asset/img_linh_vat_tf/rong_tf_like.png') }}" alt="linh thú LaptopTF">
            </div>
        </div>

        <form class="register-form" id="registerForm" action="{{ route('xuly.dangky') }}" method="POST">
            @csrf
            
            <h3 class="form-section-title">Thông tin cá nhân</h3>
            
            <div class="form-row">
                <div class="form-group" id="group-ho_ten">
                    <label>Họ và tên <span style="color:red">*</span></label>
                    <input type="text" name="ho_ten" id="ho_ten" placeholder="Ví dụ: Nguyễn Văn A" value="{{ old('ho_ten') }}">
                    <div class="form-error-message"><i class="ri-error-warning-fill"></i> <span></span></div>
                </div>
               <div class="form-group" id="group-ngay_sinh">
    <label>Ngày sinh <span style="color:red">*</span></label>
    <div class="input-with-icon">
        <input type="text" 
               name="ngay_sinh" 
               id="ngay_sinh" 
               class="input-date" 
               placeholder="dd/mm/yyyy" 
               value="{{ old('ngay_sinh') }}" 
               autocomplete="off">
        
        <i class="ri-calendar-line trigger-date"></i>
    </div>
    <div class="form-error-message"><i class="ri-error-warning-fill"></i> <span></span></div>
</div>
            </div>

            <div class="form-group" style="margin: 25px 0;">
                <label>Giới tính</label>
                <div style="display: flex; gap: 20px; margin-top: 1px;">
                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer">
                        <input type="radio" name="gioi_tinh" value="1" checked> Nam
                    </label>
                    <label style="display:flex; align-items:center; gap:5px; cursor:pointer">
                        <input type="radio" name="gioi_tinh" value="0"> Nữ
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" id="group-so_dien_thoai">
                    <label>Số điện thoại <span style="color:red">*</span></label>
                    <input type="text" name="so_dien_thoai" id="so_dien_thoai" placeholder="Nhập số điện thoại (10 số)" value="{{ old('so_dien_thoai') }}">
                    <div class="form-error-message"><i class="ri-error-warning-fill"></i> <span></span></div>
                </div>
               <div class="form-group" id="group-email">
                <label>Email <span style="color:red">*</span></label>
                <input type="text" name="email" id="email" placeholder="Nhập email (abc@gmail.com)" value="{{ old('email') }}">
                
                <p class="note-text default-state" id="email-helper">
                    <i class="ri-checkbox-circle-fill"></i> Dùng để nhận hóa đơn VAT
                </p>
            </div>
            </div>

           <div class="form-group" id="group-dia_chi">
                <label>Địa chỉ <span style="color:red">*</span></label>
                <input type="text" name="dia_chi" id="dia_chi" placeholder="Số nhà, tên đường, phường/xã..." value="{{ old('dia_chi') }}">
                <div class="form-error-message"><i class="ri-error-warning-fill"></i> <span></span></div>
            </div>

            <h3 class="form-section-title">Tạo mật khẩu</h3>
            <div class="form-row">
                <div class="form-group" id="group-mat_khau">
                    <label>Mật khẩu <span style="color:red">*</span></label>
                    <div class="input-with-icon">
                        <input type="password" name="mat_khau" id="mat_khau" placeholder="Nhập mật khẩu">
                        <i class="ri-eye-off-line toggle-password"></i>
                    </div>
                    
                    <p class="note-text" id="pass-helper">
                        <i class="ri-information-fill"></i> Tối thiểu 6 ký tự, gồm chữ cái và số
                    </p>
                    
                </div>
                <div class="form-group" id="group-nhap_lai_mat_khau">
                    <label>Nhập lại mật khẩu <span style="color:red">*</span></label>
                    <div class="input-with-icon">
                        <input type="password" name="nhap_lai_mat_khau" id="nhap_lai_mat_khau" placeholder="Nhập lại mật khẩu">
                        <i class="ri-eye-off-line toggle-password"></i>
                    </div>
                    <div class="form-error-message"><i class="ri-error-warning-fill"></i> <span></span></div>
                </div>
            </div>

            <div class="policy-container">
    <label class="cps-checkbox">
        <input type="checkbox" name="newsletter" value="1">
        <span class="checkmark"></span>
        <span class="label-text">Đăng ký nhận tin khuyến mãi từ LaptopTF</span>
    </label>

    <p class="policy-text">
        Bằng việc Đăng ký, bạn đã đọc và đồng ý với 
        <a href="#" target="_blank">Điều khoản sử dụng</a> và 
        <a href="#" target="_blank">Chính sách bảo mật của LaptopTF</a>.
    </p>
</div>

            <div class="bottom-actions">
                <a href="{{ route('login') }}" class="btn-back"><i class="ri-arrow-left-s-line"></i> Quay lại đăng nhập</a>
                <button type="submit" class="btn-submit" id="btnSubmit">Hoàn tất đăng ký</button>
            </div>
        </form>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Hứng dữ liệu từ Laravel (Dùng PHP echo để không bị báo đỏ)
        var errors = <?php echo json_encode($errors->all()); ?>;
        var successMsg = <?php echo json_encode(session('success')); ?>;

        // 2. Xử lý hiển thị bằng Javascript thuần (VS Code sẽ rất thích điều này)
        
        // Nếu có lỗi -> Hiện Toast
        if (errors && errors.length > 0) {
            errors.forEach(function(err) {
                createToast("Lỗi đăng ký", err);
            });
        }

        // Nếu thành công -> Hiện Alert
        if (successMsg) {
            alert(successMsg);
        }
    });
</script>

    <script src="{{ asset('asset/js/register.js') }}"></script>
</body>
</html>