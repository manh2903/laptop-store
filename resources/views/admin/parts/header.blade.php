<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-20 relative">
    
    {{-- Nút Toggle Menu (Mobile) --}}
    <div class="flex items-center gap-4">
        <button class="text-gray-500 hover:text-blue-600 focus:outline-none lg:hidden">
            <i class="fas fa-bars text-xl"></i>
        </button>
        {{-- Nút xem website --}}
        <a href="/" target="_blank" class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-blue-600 transition bg-gray-50 px-3 py-1.5 rounded-full border border-gray-200">
            <i class="fas fa-external-link-alt text-xs"></i> Xem Website
        </a>
    </div>

    <div class="flex items-center gap-6">
        
        {{-- Nút Thông báo --}}
        <button class="relative text-gray-400 hover:text-blue-600 transition">
            <i class="far fa-bell text-xl"></i>
            <span class="absolute -top-1 -right-1 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

        {{-- ================= TÀI KHOẢN ADMIN (DROPDOWN) ================= --}}
        <div class="relative" id="user-dropdown">
            
            {{-- Nút bấm để mở menu --}}
            <button onclick="toggleUserMenu()" class="flex items-center gap-3 focus:outline-none group">
                <div class="text-right hidden md:block">
                    {{-- Tên Admin (Sau này thay bằng {{ Auth::user()->name }}) --}}
                    <p class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition">Admin Controller</p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase">Quản trị viên</p>
                </div>
                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white group-hover:ring-blue-100 transition">
                    AD
                </div>
                <i class="fas fa-chevron-down text-gray-300 text-xs group-hover:text-blue-500 transition"></i>
            </button>

            {{-- Menu thả xuống (Mặc định ẩn: hidden) --}}
            <div id="user-menu-content" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-gray-100 py-2 origin-top-right transition-all duration-200 transform">
                
                {{-- Mũi tên nhỏ trỏ lên --}}
                <div class="absolute -top-1.5 right-4 h-3 w-3 bg-white border-l border-t border-gray-100 transform rotate-45"></div>

                <div class="px-4 py-2 border-b border-gray-50 mb-1">
                    <p class="text-xs text-gray-500">Đang đăng nhập:</p>
                    <p class="text-sm font-bold text-gray-800 truncate">admin@laptoptf.com</p>
                </div>

                <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                    <i class="far fa-user w-4"></i> Hồ sơ cá nhân
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2">
                    <i class="fas fa-cog w-4"></i> Cài đặt
                </a>
                
                <div class="border-t border-gray-50 my-1"></div>

                {{-- Nút Đăng xuất --}}
                <form method="POST" action="#"> {{-- Sau này thêm route logout vào action --}}
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 hover:text-red-700 transition flex items-center gap-2 font-medium">
                        <i class="fas fa-sign-out-alt w-4"></i> Đăng xuất
                    </button>
                </form>
            </div>

        </div>

    </div>

    {{-- Script xử lý đóng mở Menu --}}
    <script>
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu-content');
            menu.classList.toggle('hidden');
        }

        // Click ra ngoài thì tự đóng menu
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            const menu = document.getElementById('user-menu-content');
            if (!dropdown.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</header>