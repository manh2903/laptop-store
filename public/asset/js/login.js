document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const phoneInput = document.getElementById('so_dien_thoai');
    const passInput = document.getElementById('mat_khau');
    const btnLogin = document.getElementById('btnLogin');

    // Regex kiểm tra số điện thoại Việt Nam
    const regexPhone = /(84|0[3|5|7|8|9])+([0-9]{8})\b/;

    // 1. Xử lý khi bấm nút Đăng nhập
    form.addEventListener('submit', function(e) {
        let hasError = false;

        // Reset lỗi cũ trước khi kiểm tra
        clearError(phoneInput);
        clearError(passInput);

        // --- KIỂM TRA SỐ ĐIỆN THOẠI ---
        const phoneVal = phoneInput.value.trim();
        if (phoneVal === '') {
            showError(phoneInput, 'Vui lòng nhập số điện thoại');
            hasError = true;
        } else if (!regexPhone.test(phoneVal)) {
            showError(phoneInput, 'Số điện thoại không đúng định dạng');
            hasError = true;
        }

        // --- KIỂM TRA MẬT KHẨU ---
        const passVal = passInput.value.trim();
        if (passVal === '') {
            showError(passInput, 'Vui lòng nhập mật khẩu');
            hasError = true;
        } else if (passVal.length < 6) {
            showError(passInput, 'Mật khẩu phải có ít nhất 6 ký tự');
            hasError = true;
        }

        // Nếu có lỗi thì chặn gửi form
        if (hasError) {
            e.preventDefault();
        } else {
            // Nếu không lỗi, tạo hiệu ứng loading
            btnLogin.innerText = "Đang xử lý...";
            btnLogin.style.opacity = "0.7";
            btnLogin.style.pointerEvents = "none";
        }
    });

    // 2. Tự động xóa lỗi khi người dùng bắt đầu nhập lại
    phoneInput.addEventListener('input', function() {
        clearError(this);
    });

    passInput.addEventListener('input', function() {
        clearError(this);
    });

    // --- HÀM HỖ TRỢ ---
    
    // Hàm hiện lỗi
    function showError(input, message) {
        const formGroup = input.closest('.form-group'); // Tìm thẻ cha
        const errorDiv = formGroup.querySelector('.text-danger');
        
        formGroup.classList.add('error'); // Thêm viền đỏ (CSS đã viết)
        errorDiv.innerHTML = `<i class="ri-error-warning-fill"></i> ${message}`;
        errorDiv.style.display = 'flex';
    }

    // Hàm xóa lỗi
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        const errorDiv = formGroup.querySelector('.text-danger');
        
        formGroup.classList.remove('error'); // Bỏ viền đỏ
        errorDiv.innerHTML = ''; // Xóa chữ
    }
});