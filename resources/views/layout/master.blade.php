<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaptopTF')</title>
    <link rel="icon" href="{{ asset('asset/img/logotf.png') }}" type="image/png"/>
    <!-- Kết nối font chữ và icon thư viện -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Font chữ Inter, Dancing Script, Pacifico -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Pacifico&display=swap" rel="stylesheet">
     <!-- Icon thư viện Remixicon & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- SweetAlert2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CSS chung toàn trang -->
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    
    @yield('css')
</head>
<body data-logged-in="{{ Auth::check() ? 'true' : 'false' }}">
    @csrf
    <!-- Đầu trang -->
    @include('layout.header')

    <!-- Nội dung chính -->
    <main>
        @yield('content')
    </main>

    <!-- Chân trang -->
    @if(!isset($hideFooter))
    @include('layout.footer')
    @endif
    
    <!-- Modals -->
     @stack('modal_stack')

    <script src="{{ asset('asset/js/script.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tìm nút User và Menu theo ID
            var userBtn = document.getElementById('user-profile-btn');
            var userDropdown = document.getElementById('userDropdown');

            if (userBtn && userDropdown) {
                // Xử lý click nút User
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation(); // Chặn lan truyền
                    userDropdown.classList.toggle('show'); // Bật/Tắt class show
                });

                // Xử lý click ra ngoài thì đóng
                window.addEventListener('click', function(e) {
                    if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('show');
                    }
                });
            }
        });
    </script>

    @yield('js')
<div id="toast-container"></div>

<script>
    // Hàm hiển thị thông báo Global
    // type: 'success', 'error', 'warning'
    window.showToast = function(title, message, type = 'success') {
        const main = document.getElementById('toast-container');
        if (main) {
            const toast = document.createElement('div');
            
            // Icon tương ứng
            const icons = {
                success: 'ri-checkbox-circle-fill',
                error: 'ri-error-warning-fill',
                warning: 'ri-alert-fill'
            };
            const icon = icons[type];

            toast.classList.add('toast-message', type);
            toast.innerHTML = `
                <div class="icon">
                    <i class="${icon}"></i>
                </div>
                <div class="content">
                    <div class="title">${title}</div>
                    <div class="message">${message}</div>
                </div>
                <div class="close" onclick="this.parentElement.remove()">
                    <i class="ri-close-line" style="font-size: 16px; color: #999; cursor: pointer;"></i>
                </div>
            `;
            
            main.appendChild(toast);

            // Tự động xóa sau 3.5 giây (3s hiện + 0.5s fadeOut)
            setTimeout(function() {
                if(toast) toast.remove();
            }, 3500);
        }
    }
</script>
</body>
</html>