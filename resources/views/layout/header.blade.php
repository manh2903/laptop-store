<header>
       <div class="container">
           <div class="row-flex">
            <div class="header-bar-icon">
                <i class="ri-menu-line"></i>
            </div>
               <div class="header-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('asset/img/logolaptoptfd.png') }}" alt="Logo-thuong-hieu">
                        </a>
                    </div>

                    <div class="header-logo-mobile">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('asset/img/logotf.png') }}" alt="logo-mobile">
                        </a>
                    </div>
                     <div class="header-search">
                       <input type="text" placeholder="Tìm kiếm sản phẩm">
                        <i class="ri-search-line"></i>
                     </div>                 
                     <div class="header-nav">
                        <nav>
                            <ul>
                                <li><a href="tel:0999999999"><i class="ri-headphone-line"></i><div class="text-box"><span>Hotline</span><span>0999 999 999</span></div></a></li>
                                <li><a href="#"><i class="ri-map-pin-line"></i><div class="text-box"><span>Hệ thống</span><span>Showroom</span></div></a></li>
                                <li><a href="#"> <i class="ri-file-list-3-line"></i><div class="text-box"><span>Tra cứu</span><span>đơn hàng</span></div></a></li>
                               
{{-- Bản PC --}}
<li class="cart-li">
    <a href="{{ route('cart.index') }}"> {{-- Thay đổi tại đây --}}
        <i class="ri-shopping-cart-2-line"></i>
        <div class="text-box">
            <span>Giỏ</span>
            <span>hàng</span>
        </div>
        <div class="cart-badge" id="cart-badge-pc">
            @if(Auth::check())
                {{ \App\Models\ChiTietGioHang::whereHas('gioHang', function($q) {
                    $q->where('id_nguoi_dung', Auth::id());
                })->sum('so_luong') ?? 0 }}
            @else
                0
            @endif
        </div>
    </a>
</li>
                            </ul>
                        </nav>
                     </div>
                     <div class="header-cart-mobile">
                <i class="ri-shopping-cart-2-line"></i>
                <span class="cart-badge-mobile">0</span>
             </div>
                     @if(Auth::check())
    @php
        $parts = explode(' ', Auth::user()->ho_ten);
        $tenCuoi = array_pop($parts);
    @endphp
    
    <div id="user-menu-wrapper" class="user-dropdown-wrapper" style="position: relative; display: inline-block;">
        
        <div class="user-profile" id="user-profile-btn" style="display: flex; align-items: center; background: #d70018; padding: 4px 12px 4px 6px; border-radius: 50px; color: #fff; cursor: pointer; margin-left: 10px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
            @if(Auth::user()->anh_dai_dien)
                <img src="{{ asset(Auth::user()->anh_dai_dien) }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 1px solid #fff; background: #fff;">
            @else
                <i class="ri-account-circle-line" style="font-size: 28px; margin-right: 6px;"></i>
            @endif
            <span style="font-weight: 600; font-size: 14px; text-transform: capitalize;">{{ $tenCuoi }}</span>
        </div>

        <div id="userDropdown" class="dropdown-menu">
            <div class="menu-header">
                <span class="full-name">{{ Auth::user()->ho_ten }}</span>
                <span class="role-badge">Thành viên</span>
            </div>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 5px 0;">

            <ul class="user-menu-list">
                <li><a href="{{ route('profile') }}"><i class="ri-user-settings-line"></i> Tài khoản của tôi </a></li>      
                <li><a href="#"><i class="ri-file-list-3-line"></i> Đơn hàng của tôi</a></li>
                
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #d70018;">
                        <i class="ri-logout-box-r-line"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

@else
   <div class="login-btn" onclick="openLoginModal()"  style="cursor: pointer;">
        <i class="ri-user-line"></i>
        <div class="text-box">
            <span>Đăng</span>
            <span>nhập</span>
        </div>
    </div>
@endif
           </div>
       </div>
    </header>