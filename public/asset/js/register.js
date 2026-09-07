// === QUẢN LÝ DANH SÁCH TOAST ĐỂ XẾP CHỒNG (CODE CỦA BẠN GIỮ NGUYÊN) ===
let toasts = []; 

function createToast(title, message) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.classList.add('toast-message');
    toast.innerHTML = `
        <i class="ri-error-warning-fill icon-error"></i>
        <div class="content">
            <span class="title">${title}</span>
            <span class="desc">${message}</span>
        </div>
        <i class="ri-close-line close"></i>
    `;
    container.appendChild(toast);
    toasts.unshift(toast); 
    toast.querySelector('.close').onclick = () => removeToast(toast);
    updateToastPositions();
    setTimeout(() => removeToast(toast), 3500);
}

function removeToast(toastElement) {
    const index = toasts.indexOf(toastElement);
    if (index > -1) {
        toasts.splice(index, 1);
        toastElement.style.opacity = '0';
        toastElement.style.transform = 'translateY(-20px) scale(0.9)';
        setTimeout(() => toastElement.remove(), 300);
        updateToastPositions();
    }
}

function updateToastPositions() {
    toasts.forEach((toast, index) => {
        if (index >= 3) {
            toast.style.opacity = '0';
            toast.style.pointerEvents = 'none';
            return;
        }
        const offset = index * 12; 
        const scale = 1 - (index * 0.05);
        const zIndex = 100 - index; 
        toast.style.opacity = '1';
        toast.style.transform = `translateY(${offset}px) scale(${scale})`;
        toast.style.zIndex = zIndex;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================================
    // 1. KÍCH HOẠT LỊCH (ĐÃ SỬA THEO Ý BẠN: MỞ RA LÀ HÔM NAY)
    // ============================================================
    const dateInput = document.getElementById('ngay_sinh');
    const dateIcon = document.querySelector('.trigger-date');

    // Kiểm tra xem thư viện đã tải chưa
    if (typeof flatpickr !== 'function') {
        console.error("LỖI: Bạn chưa nhúng thẻ script flatpickr vào file HTML!");
    } else if (dateInput) {
        // Khởi tạo lịch
        const fp = flatpickr(dateInput, {
            locale: "vn",
            dateFormat: "d/m/Y",
            allowInput: true,       // Cho phép gõ ngày bằng tay
            disableMobile: "true",
            clickOpens: true,       // Bấm vào ô input cũng mở
            
            // --- CẤU HÌNH MỚI ---
            maxDate: "today",       // Chặn chọn ngày tương lai
            // defaultDate: "01/01/2000" -> ĐÃ XÓA DÒNG NÀY (Mặc định sẽ là Today)
            
            // Hiện mũi tên chuyển tháng rõ ràng hơn
            nextArrow: '<i class="ri-arrow-right-s-line"></i>',
            prevArrow: '<i class="ri-arrow-left-s-line"></i>'
        });

        // Bắt sự kiện bấm icon
        if (dateIcon) {
            dateIcon.addEventListener('click', function(e) {
                e.preventDefault(); 
                e.stopPropagation();
                fp.open(); // Mở lịch ngay
            });
        }
    }

    // ============================================================
    // 2. UI: Ẩn hiện mật khẩu (CODE CỦA BẠN GIỮ NGUYÊN)
    // ============================================================
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.previousElementSibling;
            input.type = input.type === 'password' ? 'text' : 'password';
            this.classList.toggle('ri-eye-line');
            this.classList.toggle('ri-eye-off-line');
        });
    });

    // ============================================================
    // 3. LOGIC VALIDATION (CODE CỦA BẠN GIỮ NGUYÊN)
    // ============================================================
    const form = document.getElementById('registerForm');

    function showInlineError(inputID, msg) {
        const group = document.getElementById(`group-${inputID}`);
        if(group) {
            group.classList.add('error');
            const msgSpan = group.querySelector('.form-error-message span');
            if(msgSpan) msgSpan.innerText = msg;
        }
    }
    
    function clearErrors() {
        document.querySelectorAll('.form-group').forEach(g => g.classList.remove('error'));
    }

    // Regex
    const regexName = /^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵýỷỹ\s]{2,}$/;
    const regexPhone = /^(03|05|07|08|09)+([0-9]{8})$/;
    const regexPass = /^(?=.*[A-Za-z])(?=.*\d).{6,}$/;
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Live Check Pass
    const passInput = document.getElementById('mat_khau');
    const passHelper = document.getElementById('pass-helper');
    if(passInput) {
        passInput.addEventListener('input', function() {
            const val = this.value;
            if (regexPass.test(val)) {
                this.classList.add('valid-pass');
                this.classList.remove('error');
                if(passHelper) {
                    passHelper.classList.add('valid-pass');
                    passHelper.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Mật khẩu hợp lệ';
                }
                document.getElementById('group-mat_khau')?.classList.remove('error');
            } else {
                this.classList.remove('valid-pass');
                if(passHelper) {
                    passHelper.classList.remove('valid-pass');
                    passHelper.innerHTML = '<i class="ri-information-fill"></i> Tối thiểu 6 ký tự, gồm chữ cái và số';
                }
            }
        });
    }

    // Live Check Email
    const emailInput = document.getElementById('email');
    const emailHelper = document.getElementById('email-helper');
    if(emailInput) {
        emailInput.addEventListener('input', function() {
            const val = this.value.trim();
            if (val === "") {
                this.classList.remove('valid-pass', 'error');
                if(emailHelper) {
                    emailHelper.classList.remove('hidden', 'error-state');
                    emailHelper.classList.add('default-state');
                    emailHelper.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Dùng để nhận hóa đơn VAT';
                }
            } else if (regexEmail.test(val)) {
                this.classList.add('valid-pass');
                this.classList.remove('error');
                if(emailHelper) emailHelper.classList.add('hidden');
                document.getElementById('group-email')?.classList.remove('error');
            } else {
                this.classList.remove('valid-pass');
                if(emailHelper) {
                    emailHelper.classList.remove('hidden', 'default-state');
                    emailHelper.classList.add('error-state');
                    emailHelper.innerHTML = '<i class="ri-error-warning-fill"></i> Vui lòng nhập đúng định dạng Email';
                }
            }
        });
    }

    // SUBMIT EVENT (Quét toàn bộ lỗi -> Toast 1 cái đầu tiên)
    if(form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); 
            clearErrors();

            const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value.trim() : '';
            
            const ho_ten = getVal('ho_ten');
            const ngay_sinh = document.getElementById('ngay_sinh').value;
            const so_dien_thoai = getVal('so_dien_thoai');
            const email = getVal('email');
            const dia_chi = getVal('dia_chi');
            const mat_khau = getVal('mat_khau');
            const nhap_lai = getVal('nhap_lai_mat_khau');

            let firstError = null;
            let hasError = false;

            // Hàm set lỗi: Hiện đỏ inline + Lưu lỗi đầu tiên
            const setError = (id, msgInline, titleToast, msgToast) => {
                showInlineError(id, msgInline);
                hasError = true;
                if (!firstError) firstError = { id: id, title: titleToast, msg: msgToast };
            };

            // --- BẮT ĐẦU KIỂM TRA ---
            if (!ho_ten) setError('ho_ten', 'Vui lòng nhập họ tên', 'Thiếu thông tin', 'Họ tên không được bỏ trống');
            else if (!regexName.test(ho_ten) || ho_ten.split(' ').length < 2) setError('ho_ten', 'Họ tên > 2 từ, không số', 'Sai định dạng', 'Họ tên không hợp lệ');

            if (!ngay_sinh) setError('ngay_sinh', 'Vui lòng chọn ngày sinh', 'Thiếu thông tin', 'Bạn chưa chọn ngày sinh');

            if (!so_dien_thoai) setError('so_dien_thoai', 'Vui lòng nhập SĐT', 'Thiếu thông tin', 'SĐT không được bỏ trống');
            else if (/[^0-9]/.test(so_dien_thoai)) setError('so_dien_thoai', 'Chỉ được nhập số', 'Sai định dạng', 'SĐT chứa ký tự lạ');
            else if (!regexPhone.test(so_dien_thoai)) setError('so_dien_thoai', 'Đầu số 03,05,07,08,09 & đủ 10 số', 'Sai định dạng', 'SĐT không đúng định dạng VN');

            if (!email) setError('email', 'Vui lòng nhập Email', 'Thiếu thông tin', 'Email không được bỏ trống');
            else if (!regexEmail.test(email)) setError('email', 'Email không hợp lệ', 'Sai định dạng', 'Email chưa đúng chuẩn');

            if (!dia_chi) setError('dia_chi', 'Vui lòng nhập địa chỉ', 'Thiếu thông tin', 'Địa chỉ không được bỏ trống');

            if (!mat_khau) setError('mat_khau', 'Vui lòng nhập mật khẩu', 'Thiếu thông tin', 'Mật khẩu không được bỏ trống');
            else if (!regexPass.test(mat_khau)) setError('mat_khau', 'Tối thiểu 6 ký tự, gồm chữ và số', 'Lưu ý', 'Mật khẩu cần có cả chữ và số');

            if (mat_khau && nhap_lai && mat_khau !== nhap_lai) setError('nhap_lai_mat_khau', 'Mật khẩu không khớp', 'Lỗi mật khẩu', 'Xác nhận mật khẩu sai');
            else if (mat_khau && !nhap_lai) setError('nhap_lai_mat_khau', 'Vui lòng nhập lại mật khẩu', 'Thiếu thông tin', 'Chưa nhập lại mật khẩu');

            // --- KẾT THÚC KIỂM TRA ---
            
            if (hasError) {
                // Nếu có lỗi, hiện Toast của cái đầu tiên và focus vào nó
                if (firstError) {
                    createToast(firstError.title, firstError.msg);
                    const el = document.getElementById(firstError.id);
                    if(el) el.focus();
                }
                return; // Dừng, không gửi
            }

            // AJAX Check trùng SĐT
            const btn = document.getElementById('btnSubmit');
            const originalBtnText = btn.innerText;
            btn.innerText = "Đang kiểm tra...";
            btn.style.opacity = "0.7";
            btn.style.pointerEvents = "none";

            try {
                const csrfToken = document.querySelector('input[name="_token"]').value;
                const response = await fetch('/check-phone', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ phone: so_dien_thoai })
                });

                const data = await response.json();

                if (data.exists) {
                    createToast("Dữ liệu trùng", "Số điện thoại này đã được sử dụng!");
                    showInlineError('so_dien_thoai', 'SĐT này đã tồn tại trong hệ thống');
                    resetButton(btn, originalBtnText);
                    return; 
                }

                // GỬI FORM
                btn.innerText = ""; 
                btn.classList.add('btn-loading'); 
                form.submit(); 

            } catch (error) {
                console.error(error);
                createToast("Lỗi kết nối", "Lỗi kiểm tra dữ liệu, hãy thử lại.");
                resetButton(btn, originalBtnText);
            }
        });
    }

    function resetButton(btn, text) {
        btn.innerText = text;
        btn.style.opacity = "1";
        btn.style.pointerEvents = "auto";
        btn.classList.remove('btn-loading');
    }
});