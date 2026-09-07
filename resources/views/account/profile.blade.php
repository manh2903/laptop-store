@php $hideFooter = true; @endphp <!--Ẩn chân trang khi đăng nhập -->
@extends('layout.master')

@section('title', 'Quản lý tài khoản')

@section('css')
<style>
    /* --- CSS RIÊNG CHO TRANG DASHBOARD --- */
    .wrapper { max-width: 1250px; margin: 30px auto; display: flex; gap: 25px; padding: 0 15px; }

    /* 1. SIDEBAR (CỘT TRÁI) */
    .sidebar { width: 280px; flex-shrink: 0; }
    .user-card { 
        background: #fff; padding: 20px; border-radius: 12px; text-align: center; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 20px;
    }
    .user-card img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .user-card h4 { margin: 10px 0 5px; font-size: 16px; font-weight: 700; color: #333; }
    .member-rank { 
        display: inline-block; padding: 4px 12px; background: linear-gradient(45deg, #FFD700, #FDB931); 
        color: #fff; font-size: 11px; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;
    }

    .menu-box { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .menu-item { 
        display: flex; align-items: center; gap: 15px; padding: 14px 20px; cursor: pointer; 
        border-left: 3px solid transparent; transition: 0.2s; color: #555; font-size: 14px; font-weight: 500;
    }
    .menu-item i { font-size: 18px; color: #999; transition: 0.2s; }
    .menu-item:hover { background: #fafafa; color: #d70018; }
    .menu-item.active { background: #fff5f5; color: #d70018; border-left-color: #d70018; font-weight: 600; }
    .menu-item.active i { color: #d70018; }

    /* 2. CONTENT (CỘT PHẢI) */
    .content-area { flex: 1; background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); min-height: 600px; }
    .tab-content { display: none; animation: slideUp 0.3s ease; }
    .tab-content.active { display: block; }
    
    .header-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f5f5f5; padding-bottom: 15px; }
    .header-title h2 { margin: 0; font-size: 20px; color: #2d3748; }
    .last-update { font-size: 13px; color: #888; display: flex; align-items: center; gap: 5px; }

    /* WIDGET VOUCHER */
    .voucher-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .voucher-card { 
        border: 1px dashed #d70018; background: #fff5f5; border-radius: 8px; padding: 15px; 
        display: flex; justify-content: space-between; align-items: center; position: relative;
    }
    .voucher-info h4 { margin: 0 0 5px; color: #d70018; }
    .voucher-info p { margin: 0; font-size: 12px; color: #666; }
    .btn-copy { background: #d70018; color: #fff; border: none; padding: 6px 15px; border-radius: 4px; font-size: 12px; cursor: pointer; }

    /* FORM HỖ TRỢ */
    .support-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
    .contact-box { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; }
    .contact-box i { font-size: 32px; color: #d70018; margin-bottom: 10px; display: block; }
    
    /* TRA CỨU BẢO HÀNH */
    .warranty-search { display: flex; gap: 10px; margin-bottom: 20px; }
    .search-input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    .search-btn { padding: 0 25px; background: #2d3748; color: #fff; border: none; border-radius: 6px; cursor: pointer; }

    /* BUTTON STYLE */
    .btn-save { background: #d70018; color: #fff; border: none; padding: 12px 30px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.2s; }
    .btn-save:hover { background: #b00014; }

    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection

@section('content')
<div class="wrapper">
    
    <div class="sidebar">
        <div class="user-card">
            @if(Auth::user()->anh_dai_dien)
                <img src="{{ asset(Auth::user()->anh_dai_dien) }}">
            @else
                <img src="https://via.placeholder.com/150" style="background:#eee;">
            @endif
            <h4>{{ Auth::user()->ho_ten }}</h4>
            <span class="member-rank"><i class="ri-vip-crown-fill"></i> Thành viên Vàng</span>
        </div>

        <div class="menu-box">
            <div class="menu-item active" onclick="switchTab('dashboard', this)">
                <i class="ri-dashboard-3-line"></i> Tổng quan
            </div>
            <div class="menu-item" onclick="switchTab('orders', this)">
                <i class="ri-file-list-3-line"></i> Đơn hàng của tôi
            </div>
            <div class="menu-item" onclick="switchTab('vouchers', this)">
                <i class="ri-ticket-2-line"></i> Kho Voucher <span style="margin-left:auto; background:#d70018; color:#fff; font-size:10px; padding:2px 6px; border-radius:10px;">Mới</span>
            </div>
            <div class="menu-item" onclick="switchTab('warranty', this)">
                <i class="ri-shield-check-line"></i> Tra cứu bảo hành
            </div>
            <div class="menu-item" onclick="switchTab('address', this)">
                <i class="ri-map-pin-user-line"></i> Sổ địa chỉ
            </div>
            <div class="menu-item" onclick="switchTab('profile', this)">
                <i class="ri-user-settings-line"></i> Hồ sơ cá nhân
            </div>
            <div class="menu-item" onclick="switchTab('support', this)">
                <i class="ri-customer-service-2-line"></i> Hỗ trợ & Góp ý
            </div>
            <div class="menu-item" onclick="document.getElementById('logout-form').submit()" style="color:#d70018; border-top:1px solid #f0f0f0;">
                <i class="ri-logout-box-r-line"></i> Đăng xuất
            </div>
        </div>
    </div>

    <div class="content-area">

        <div id="dashboard" class="tab-content active">
            <div class="header-title">
                <h2>Tổng quan tài khoản</h2>
                <span class="last-update"><i class="ri-time-line"></i> Cập nhật: Vừa xong</span>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div style="background: #e6fffa; padding: 20px; border-radius: 10px; color: #00a67d;">
                    <h3 style="margin:0; font-size: 28px;">0</h3>
                    <span>Đơn đang giao</span>
                </div>
                <div style="background: #fff5f5; padding: 20px; border-radius: 10px; color: #d70018;">
                    <h3 style="margin:0; font-size: 28px;">2</h3>
                    <span>Voucher</span>
                </div>
                <div style="background: #ebf8ff; padding: 20px; border-radius: 10px; color: #3182ce;">
                    <h3 style="margin:0; font-size: 28px;">0đ</h3>
                    <span>Tiết kiệm được</span>
                </div>
            </div>
            
            <h3 style="font-size: 16px; margin-bottom: 15px; color:#333;">Đơn hàng gần đây</h3>
            <div style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px; color: #888;">
                <i class="ri-shopping-cart-line" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                Chưa có đơn hàng nào gần đây
            </div>
        </div>

        <div id="vouchers" class="tab-content">
            <div class="header-title">
                <h2>Kho Voucher của tôi</h2>
            </div>
            <div class="voucher-grid">
                <div class="voucher-card">
                    <div class="voucher-info">
                        <h4>Giảm 500K</h4>
                        <p>Cho đơn hàng Laptop > 15 triệu</p>
                        <p style="font-size: 11px; margin-top: 5px; color: #888;">HSD: 31/12/2025</p>
                    </div>
                    <button class="btn-copy">Dùng ngay</button>
                </div>
                <div class="voucher-card">
                    <div class="voucher-info">
                        <h4>Freeship 50K</h4>
                        <p>Áp dụng cho mọi đơn hàng</p>
                        <p style="font-size: 11px; margin-top: 5px; color: #888;">HSD: Vĩnh viễn</p>
                    </div>
                    <button class="btn-copy">Dùng ngay</button>
                </div>
            </div>
        </div>

        <div id="warranty" class="tab-content">
            <div class="header-title">
                <h2>Tra cứu bảo hành điện tử</h2>
            </div>
            <div class="warranty-search">
                <input type="text" class="search-input" placeholder="Nhập Serial Number hoặc Mã đơn hàng...">
                <button class="search-btn">Tra cứu</button>
            </div>
            <div style="margin-top: 20px;">
                <h4 style="margin-bottom: 10px; color:#333;">Thiết bị của tôi</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 10px; text-align: left;">Sản phẩm</th>
                        <th style="padding: 10px;">Ngày mua</th>
                        <th style="padding: 10px;">Hạn bảo hành</th>
                        <th style="padding: 10px;">Trạng thái</th>
                    </tr>
                    <tr>
                        <td style="padding: 15px 10px; border-bottom: 1px solid #eee;">
                            <strong>MacBook Air M1</strong><br>
                            <span style="font-size: 12px; color: #888;">SN: C02XG12345</span>
                        </td>
                        <td style="padding: 15px 10px; text-align: center; border-bottom: 1px solid #eee;">01/01/2024</td>
                        <td style="padding: 15px 10px; text-align: center; border-bottom: 1px solid #eee;">01/01/2025</td>
                        <td style="padding: 15px 10px; text-align: center; border-bottom: 1px solid #eee;">
                            <span style="background: #c6f6d5; color: #22543d; padding: 4px 10px; border-radius: 12px; font-size: 12px;">Còn hạn</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="support" class="tab-content">
            <div class="header-title">
                <h2>Trung tâm hỗ trợ khách hàng</h2>
            </div>
            <div class="support-grid">
                <div>
                    <h4 style="margin-bottom: 15px; color:#333;">Gửi yêu cầu hỗ trợ</h4>
                    <form>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-size: 13px;">Vấn đề gặp phải</label>
                            <select style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option>Bảo hành / Sửa chữa</option>
                                <option>Khiếu nại dịch vụ</option>
                                <option>Góp ý cải thiện</option>
                            </select>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-size: 13px;">Nội dung chi tiết</label>
                            <textarea rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;"></textarea>
                        </div>
                        <button class="btn-save">Gửi phản hồi</button>
                    </form>
                </div>
                <div>
                    <div class="contact-box">
                        <i class="ri-phone-fill"></i>
                        <strong>Tổng đài tư vấn</strong>
                        <p style="font-size: 18px; color: #d70018; font-weight: bold; margin: 5px 0;">0999.999.999</p>
                        <p style="font-size: 13px;">(8:00 - 22:00 hàng ngày)</p>
                    </div>
                    <div class="contact-box" style="margin-top: 15px;">
                        <i class="ri-mail-send-fill"></i>
                        <strong>Email hỗ trợ</strong>
                        <p style="font-size: 16px; margin: 5px 0;">hotro@laptoptf.com</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="profile" class="tab-content">
            <div class="header-title">
                <h2>Hồ sơ cá nhân</h2>
                <span class="last-update">Lần cập nhật cuối: 22/12/2025</span>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px;">Họ và tên</label>
                        <input type="text" class="search-input" style="width: 100%; box-sizing: border-box;" value="{{ Auth::user()->ho_ten }}">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px;">Số điện thoại</label>
                        <input type="text" class="search-input" style="width: 100%; box-sizing: border-box;" value="{{ Auth::user()->so_dien_thoai }}">
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px;">Địa chỉ nhận hàng</label>
                    <input type="text" class="search-input" style="width: 100%; box-sizing: border-box;" value="{{ Auth::user()->dia_chi }}">
                </div>
                <button class="btn-save">Lưu thay đổi</button>
            </form>
        </div>
        
        <div id="address" class="tab-content">
            <div class="header-title">
                <h2>Sổ địa chỉ nhận hàng</h2>
                <button class="btn-copy" style="cursor: pointer;">+ Thêm địa chỉ mới</button>
            </div>
            <div style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; position: relative;">
                <span style="position: absolute; right: 20px; top: 20px; color: #d70018; font-size: 13px;">Thiết lập mặc định</span>
                <h4 style="margin: 0 0 10px; color:#333;">{{ Auth::user()->ho_ten }} <span style="font-weight: normal; color: #666;">| {{ Auth::user()->so_dien_thoai }}</span></h4>
                <p style="color: #555; font-size: 14px; margin-bottom: 15px;">{{ Auth::user()->dia_chi }}</p>
                <div style="display: flex; gap: 15px;">
                    <a href="#" style="font-size: 13px; color: #2d3748;">Cập nhật</a>
                    <a href="#" style="font-size: 13px; color: #d70018;">Xóa</a>
                </div>
            </div>
        </div>

        <div id="orders" class="tab-content">
            <div class="header-title">
                <h2>Đơn hàng của tôi</h2>
            </div>
            <div style="text-align: center; color: #888; padding: 50px;">
                <i class="ri-file-list-3-line" style="font-size: 40px;"></i>
                <p>Bạn chưa có đơn hàng nào.</p>
                <a href="{{ route('home') }}" style="color: #d70018; text-decoration: underline;">Mua sắm ngay</a>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script>
    function switchTab(tabId, element) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
    }
</script>
@endsection