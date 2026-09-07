@extends('layout.master')

@section('title', 'Chi tiết sản phẩm - LaptopTF')

@section('css')
    <link rel="stylesheet" href="{{ asset('asset/css/product_details.css') }}">
@endsection


@section('content')
<input type="hidden" id="login-flag" value="{{ Auth::check() ? '1' : '0' }}">

@php
    $sanPhamChinh = $product;
@endphp
    

    <!--Phần thân-->
     <section class="main-menu">
    <div class="conmymenu">
        <div class="row-menu">
            <div class="nav-menu">
                <nav class="main-menu-bar">
                  <ul class="menu-list">                                     
                    <li class="has-mega-menu"><a href="#"><i class="ri-layout-horizontal-line"></i><span>DANH MỤC LAPTOP</span></a>
                        <div class="mega-dropdown">
                            <div class="mega-container">
                                <div class="mega-col">
                                    <h3 class="mega-title">Theo nhu cầu</h3>
                                    <ul>
                                        <li><a href="#">MacBook</a></li>
                                        <li><a href="#">Laptop Văn phòng</a></li>                                       
                                        <li><a href="#">Laptop Gaming</a></li>
                                        <li><a href="#">Laptop Đồ họa, kỹ thuật</a></li>
                                        <li><a href="#">Laptop Mỏng nhẹ</a></li>
                                        <li><a href="#">Laptop AI</a></li>
                                        <li><a href="#">Laptop 99%</a></li>                                     
                                    </ul>
                                </div>                              
                                <div class="mega-col">                                        
                                    <h3 class="mega-title">Thương hiệu</h3>
                                    <ul>
                                        <li><a href="#">MACBOOK</a></li>
                                        <li><a href="#">Laptop ASUS</a></li>
                                        <li><a href="#">Laptop Acer</a></li>
                                        <li><a href="#">Laptop Dell</a></li>
                                        <li><a href="#">Laptop HP</a></li>
                                        <li><a href="#">Laptop Lenovo</a></li>
                                        <li><a href="#">Laptop MSI</a></li>
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Giá bán</h3>
                                    <ul>
                                        <li><a href="#">Dưới 10 triệu</a></li>
                                        <li><a href="#">Từ 10 - 15 triệu</a></li>
                                        <li><a href="#">Từ 15 - 20 triệu</a></li>
                                        <li><a href="#">Từ 20 - 30 triệu</a></li>
                                        <li><a href="#">Trên 30 triệu</a></li>
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Cấu hình</h3>
                                    <ul>
                                        <li><a href="#">Core i3 / Ryzen 3</a></li>
                                        <li><a href="#">Core i5 / Ryzen 5</a></li>
                                        <li><a href="#">Core i7 / Ryzen 7</a></li>
                                        <li><a href="#">Apple M</a></li>
                                        <li><a href="#">VGA RTX 3060</a></li>
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Phụ kiện</h3>
                                    <ul>
                                        <li><a href="#">Ram / Rom / CPU</a></li>
                                        <li><a href="#">Tai nghe</a></li>
                                        <li><a href="#">Bàn phím</a></li>
                                        <li><a href="#">Chuột cơ</a></li>
                                        <li><a href="#">Loa</a></li>
                                        <li><a href="#">Bàn - Ghế Gaming</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li> 
                    <li class="has-mega-menu"><a href="#"><i class="ri-macbook-line"></i><span>LAPTOP MỚI</span></a>
                        <div class="mega-dropdown">
                            <div class="mega-container">
                                <div class="mega-col">
                                    <h3 class="mega-title">MacBook Mới</h3>
                                    <ul>
                                        <li><a href="#">MacBook Air M1</a></li>
                                        <li><a href="#">MacBook Air M2</a></li>
                                        <li><a href="#">MacBook Air M3</a></li>
                                        <li><a href="#">MacBook Pro 13 inch</a></li>
                                        <li><a href="#">MacBook Pro 14 inch</a></li>
                                        <li><a href="#">MacBook Pro 16 inch</a></li>                                       
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Laptop Dell Mới</h3>
                                    <ul>
                                        <li><a href="#">Dell Alienware</a></li>
                                        <li><a href="#">Dell Precision</a></li>
                                        <li><a href="#">Dell XPS</a></li>
                                        <li><a href="#">Dell Vostro</a></li>
                                        <li><a href="#">Dell Inspiron</a></li>
                                        <li><a href="#">Dell Latitude</a></li>
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Laptop Lenovo Mới</h3>
                                    <ul>
                                        <li><a href="#">Lenovo Ideapad</a></li>
                                        <li><a href="#">Lenovo ThinkBook</a></li>
                                        <li><a href="#">Lenovo Yoga</a></li>
                                        <li><a href="#">Lenovo Legion</a></li>
                                        <li><a href="#">Lenovo Thinkpad</a></li>
                                        <li><a href="#">Lenovo LOQ</a></li>
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Laptop Acer Mới</h3>
                                    <ul>
                                        <li><a href="#">Acer Aspire</a></li>
                                        <li><a href="#">Acer Nitro</a></li>
                                        <li><a href="#">Acer Spin</a></li>
                                        <li><a href="#">Acer Predator</a></li>
                                        <li><a href="#">Acer Swift</a></li>
                                    </ul>
                                </div>
                              <div class="mega-col">
                                    <h3 class="mega-title">Laptop HP Mới</h3>
                                    <ul>
                                        <li><a href="#">HP Elitebook</a></li>
                                        <li><a href="#">HP Spectre</a></li>
                                        <li><a href="#">HP Pavilion</a></li>
                                        <li><a href="#">HP Probook</a></li>
                                        <li><a href="#">HP Omen</a></li>
                                        <li><a href="#">HP Envy</a></li>
                                    </ul>
                                </div>                   
                                <div class="mega-col">
                                    <h3 class="mega-title">Laptop Asus Mới</h3>
                                    <ul>
                                        <li><a href="#">Asus Tuf</a></li>
                                        <li><a href="#">Asus ZenBook</a></li>
                                        <li><a href="#">Asus Rog</a></li>
                                    </ul>
                                </div>
                                <div class="mega-col">
                                    <h3 class="mega-title">Laptop MSI Mới</h3>
                                    <ul>
                                        <li><a href="#">MSI G-Seri</a></li>
                                        <li><a href="#">MSI Bravo</a></li>
                                        <li><a href="#">MSI Modern</a></li>
                                    </ul>
                                </div>
                               
                            </div>
                        </div>
                    </li>
                    <li><a href="#"><i class="ri-discount-percent-line"></i> <span>KHUYẾN MÃI</span></a></li>
                    <li><a href="#"><i class="ri-news-line"></i><span>TIN TỨC</span></a></li>
                    <li><a href="#"><i class="ri-exchange-line"></i><span>THU CŨ ĐỔI MỚI</span></a></li>
                    <li><a href="#"><i class="ri-wrench-line"></i><span>TRA CỨU BẢO HÀNH</span></a></li>
                  </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<!--Chi tiết sản phẩm-->

<section class="product-detail">
  <div class="container">
     <div class="breadcrumb-content">
        {{-- 1. Trang chủ --}}
        <a href="/"><i class="ri-home-line"></i> Trang chủ</a> 
        <span class="sep">/</span> 
        
        {{-- 2. Thương hiệu (Ví dụ: Asus/Dell/MacBook) --}}
        {{-- Kiểm tra nếu có quan hệ thuongHieu thì hiển thị --}}
        @if($sanPham->thuongHieu)
            <a href="#">{{ $sanPham->thuongHieu->ten_thuong_hieu }}</a> 
            <span class="sep">/</span>
        @endif

        {{-- 3. Tên sản phẩm hiện tại --}}
        <span class="current">{{ $sanPham->ten_san_pham }}</span>
    </div>

    {{-- 5. Tên sản phẩm (H1) --}}
    <h1>{{ $sanPham->ten_san_pham }}</h1>

    {{-- 6. Đánh giá sao & Số lượng đánh giá --}}
    <div class="rating">
        {{-- Hiển thị số sao trung bình (Làm tròn 1 số thập phân) --}}
        ⭐ {{ number_format($diemTrungBinh, 1) }} 
        {{-- Hiển thị tổng số đánh giá --}}
        ({{ $tongDanhGia }} đánh giá)
    </div>

    <div class="actions">
    <span>♡ Yêu thích</span>
    <span>💬 Hỏi đáp</span>
    
    {{-- Nút Scroll xuống thông số --}}
    <span class="action-btn" id="btnScrollToSpecs">
        ⚙️ Thông số
    </span>

    {{-- Nút So sánh (Lấy ID sản phẩm từ Blade) --}}
    <span class="action-btn" id="btnCompare" data-id="{{ $sanPham->id }}">
        ⇄ So sánh
    </span>
</div>

    <div class="product">
        <!------ Phần gallery ảnh sản phẩm ----->
  <div class="product-gallery-wrapper">
    <div class="gallery-main-container">
        {{-- Nút Prev ảnh to --}}
        <button class="nav-arrow prev-arrow" id="mainPrevBtn">
            <i class="ri-arrow-left-s-line"></i>
        </button>
        
        <div class="main-image-wrapper">
            {{-- 1. KHUNG HIỂN THỊ ẢNH (Mặc định hiển thị ảnh đại diện) --}}
            <div id="imageContainer" class="media-container full-layout" style="display: block;">
                <img src="{{ asset($sanPham->anh_dai_dien) }}" 
                     data-zoom-image="{{ asset($sanPham->anh_dai_dien) }}" 
                     alt="{{ $sanPham->ten_san_pham }}" 
                     id="mainImg">
            </div>
            
            {{-- 2. KHUNG HIỂN THỊ VIDEO --}}
            <div id="videoContainer" class="media-container full-layout" style="display: none;">
                <div class="video-wrapper">
                    @if(!empty($sanPham->video_url))
                        <div id="customPlayBtn" class="custom-play-btn" title="Phát Video"></div>
                        <video id="mainVideo" controls>
                            <source src="{{ asset($sanPham->video_url) }}" type="video/mp4">
                            Trình duyệt không hỗ trợ video.
                        </video>
                    @else
                        {{-- Hiển thị khi chưa có video --}}
                        <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#fff; flex-direction:column;">
                            <i class="ri-movie-off-line" style="font-size: 3rem;"></i>
                            <p style="margin-top: 10px">Video đang được cập nhật...</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 3. KHUNG HIỂN THỊ TÍNH NĂNG NỔI BẬT --}}
            <div id="specialFeatureBox" class="media-container feature-layout" style="display: none;">
                <div class="feature-inner">
                    <div class="feat-left">
                        <div class="feat-img-card">
                            @php
                                $featureImg = $sanPham->hinhAnh->where('loai', 'feature')->first();
                                $featureImgSrc = $featureImg ? asset($featureImg->duong_dan_anh) : asset($sanPham->anh_dai_dien);
                            @endphp
                            <img src="{{ $featureImgSrc }}" alt="Feature">
                        </div>
                    </div>
                    <div class="feat-right">
                        <h3>TÍNH NĂNG NỔI BẬT</h3>
                        <ul>
                            @if($sanPham->tinh_nang_noi_bat)
                                @foreach(explode("\n", $sanPham->tinh_nang_noi_bat) as $line)
                                    @if(trim($line))
                                        <li>{{ trim($line) }}</li>
                                    @endif
                                @endforeach
                            @else
                                <li>Nội dung tính năng đang được cập nhật...</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nút Next ảnh to --}}
        <button class="nav-arrow next-arrow" id="mainNextBtn">
            <i class="ri-arrow-right-s-line"></i>
        </button>
    </div>

    {{-- PHẦN THUMBNAILS (Danh sách nhỏ bên dưới) --}}
    <div class="thumbnails-wrapper">
        <button class="thumb-nav thumb-prev" id="thumbPrevBtn"><i class="ri-arrow-left-s-line"></i></button>

        <div class="thumbnail-container" id="thumbContainer">
            
            {{-- A. Nút Video (LUÔN HIỆN) --}}
            <div class="thumb-item active" data-type="video" onclick="showMedia('video')">
                <div class="thumb-content"><i class="ri-play-circle-line"></i><span>Video</span></div>
            </div>

            {{-- B. Nút Tính năng nổi bật (LUÔN HIỆN) --}}
            <div class="thumb-item" data-type="feature" onclick="showMedia('feature')">
                <div class="thumb-content"><i class="ri-star-line"></i><span>Tính năng<br>nổi bật</span></div>
            </div>

           

            {{-- D. Vòng lặp các ảnh Gallery (SỬ DỤNG 3 CỘT DỮ LIỆU) --}}
            @if($sanPham->hinhAnh)
                @foreach($sanPham->hinhAnh as $img)
                    @if($img->loai == 'gallery')
                    
                    {{-- Mỗi item chứa đủ 3 link ảnh: Nhỏ - Thường - Lớn --}}
                    <div class="thumb-item" 
                         onclick="showMedia('image', '{{ asset($img->duong_dan_anh) }}')"
                         data-type="image"
                         
                         {{-- 1. Ảnh THƯỜNG (Dùng cho khung chính) --}}
                         data-medium="{{ asset($img->duong_dan_anh) }}" 
                         
                         {{-- 2. Ảnh LỚN (Dùng cho Zoom - Nếu ko có thì lấy ảnh thường) --}}
                         data-large="{{ asset($img->anh_lon ?? $img->duong_dan_anh) }}">
                        
                        {{-- 3. Ảnh NHỎ (Dùng hiển thị thumbnail - Nếu ko có thì lấy ảnh thường) --}}
                        <img src="{{ asset($img->anh_nho ?? $img->duong_dan_anh) }}" alt="thumb">
                    
                    </div>
                    
                    @endif
                @endforeach
            @endif
            
        </div>

        <button class="thumb-nav thumb-next" id="thumbNextBtn"><i class="ri-arrow-right-s-line"></i></button>
    </div>


<!----------- Phần cam kết sản phẩm  -------->
<div class="policy-box-tf">
    <h3 class="policy-title">LaptopTF cam kết</h3>
    
    <div class="policy-grid">
        <div class="policy-item">
            <i class="ri-box-3-line"></i>
            <div class="policy-content">
                <span>Sản phẩm mới 100% chính hãng</span>
                <p>(Nguyên seal, chưa active, đầy đủ phụ kiện)</p>
            </div>
        </div>

        <div class="policy-item">
            <i class="ri-inbox-archive-line"></i>
            <div class="policy-content">
                <span>Bộ sản phẩm bao gồm:</span>
                <p>Hộp, Sách hướng dẫn, Cây lấy sim, Cáp sạc</p>
            </div>
        </div>

        <div class="policy-item">
            <i class="ri-refresh-line"></i>
            <div class="policy-content">
                <span>1 đổi 1 trong 30 ngày</span>
                <p>Nếu có lỗi phần cứng từ nhà sản xuất. <a href="#">Xem chi tiết</a></p>
            </div>
        </div>

        <div class="policy-item">
            <i class="ri-shield-check-line"></i>
            <div class="policy-content">
                <span>Bảo hành chính hãng 1 năm</span>
                <p>Tại các trung tâm bảo hành hãng toàn quốc. <a href="#">Xem địa chỉ</a></p>
            </div>
        </div>
    </div>
</div>

<!----------- Phần thông số kỹ thuật nhanh ---------->
<div id="thong-so-ky-thuat" class="specs-box-clean">
    <div class="specs-header-clean">
        <h3>Thông số kỹ thuật</h3>
    </div>
    
  <div class="specs-table-preview">
    <table class="table-simple">
        <tbody>
            <tr>
                <td>Loại card đồ họa</td>
                <td>{{ $sanPham->thongSo->loai_card_do_hoa ?? 'Đang cập nhật' }}</td>
            </tr>
            <tr>
                <td>Dung lượng RAM</td>
                <td>{{ $sanPham->thongSo->ram ?? 'Đang cập nhật' }}</td>
            </tr>
            <tr>
                <td>Ổ cứng</td>
                <td>{{ $sanPham->thongSo->o_cung ?? 'Đang cập nhật' }}</td>
            </tr>
            <tr>
                <td>Kích thước màn hình</td>
                <td>{{ $sanPham->thongSo->kich_thuoc_man_hinh ?? 'Đang cập nhật' }}</td>
            </tr>
            <tr>
                <td>Công nghệ màn hình</td>
                <td>{{ $sanPham->thongSo->cong_nghe_man_hinh ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pin</td>
                <td>{{ $sanPham->thongSo->pin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Hệ điều hành</td>
                <td>{{ $sanPham->thongSo->he_dieu_hanh ?? '-' }}</td>
            </tr>
            <tr>
                <td>Độ phân giải</td>
                <td>{{ $sanPham->thongSo->do_phan_giai ?? '-' }}</td>
            </tr>
            <tr>
                <td>Loại CPU</td>
                <td>{{ $sanPham->thongSo->cong_nghe_cpu ?? '-' }}</td>
            </tr>
            <tr>
                <td>Cổng giao tiếp</td>
                <td>{{ $sanPham->thongSo->cong_giao_tiep ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
</div>

    <div class="specs-box-footer">
        <button id="openSpecsModalBtn" class="btn-detail-outline-red">
            Xem cấu hình chi tiết <i class="ri-arrow-right-s-line"></i>
        </button>
    </div>
</div>

<div id="full-specs-modal" class="specs-modal-overlay">
    <div class="specs-modal-container-large">
        <div class="specs-modal-header-large">
            <h3>Thông số kĩ thuật chi tiết</h3>
            <button class="close-specs-modal" id="closeFullSpecsBtn"><i class="ri-close-line"></i></button>
        </div>

        <div class="specs-tabs-bar">
            <span class="tab-item active" data-target="group-cpu">Bộ xử lý & Đồ họa</span>
            <span class="tab-item" data-target="group-memory">Bộ nhớ Ram, ổ cứng</span>
            <span class="tab-item" data-target="group-screen">Màn hình</span>
            <span class="tab-item" data-target="group-sound">Âm thanh</span>
            <span class="tab-item" data-target="group-size-weight">Kích thước & Trọng lượng</span>
            <span class="tab-item" data-target="group-other">Tiện ích & thông số khác</span>
        </div>
        
       <div class="specs-modal-body-large" id="modalBodyScroll">
    
    {{-- Nhóm 1: CPU & Đồ họa --}}
    <div class="spec-group-block" id="group-cpu">
        <h4 class="group-title">Bộ xử lý & Đồ họa</h4>
        <table class="table-specs-detail">
            <tbody>
                <tr><td>Công nghệ CPU</td><td>{{ $sanPham->thongSo->cong_nghe_cpu ?? '-' }}</td></tr>
                <tr><td>Số nhân</td><td>{{ $sanPham->thongSo->so_nhan ?? '-' }}</td></tr>
                <tr><td>Loại card đồ họa</td><td>{{ $sanPham->thongSo->loai_card_do_hoa ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Nhóm 2: RAM & Ổ cứng --}}
    <div class="spec-group-block" id="group-memory">
        <h4 class="group-title">Bộ nhớ Ram, ổ cứng</h4>
        <table class="table-specs-detail">
            <tbody>
                <tr><td>Dung lượng RAM</td><td>{{ $sanPham->thongSo->ram ?? '-' }}</td></tr>
                <tr><td>Ổ cứng</td><td>{{ $sanPham->thongSo->o_cung ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Nhóm 3: Màn hình --}}
    <div class="spec-group-block" id="group-screen">
        <h4 class="group-title">Màn hình</h4>
        <table class="table-specs-detail">
            <tbody>
                <tr><td>Kích thước màn hình</td><td>{{ $sanPham->thongSo->kich_thuoc_man_hinh ?? '-' }}</td></tr>
                <tr><td>Độ phân giải</td><td>{{ $sanPham->thongSo->do_phan_giai ?? '-' }}</td></tr>
                <tr><td>Công nghệ màn hình</td><td>{{ $sanPham->thongSo->cong_nghe_man_hinh ?? '-' }}</td></tr>
                <tr><td>Tấm nền</td><td>{{ $sanPham->thongSo->tam_nen ?? '-' }}</td></tr>
                <tr><td>Tần số quét</td><td>{{ $sanPham->thongSo->tan_so_quet ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Nhóm 4: Âm thanh --}}
    <div class="spec-group-block" id="group-sound">
        <h4 class="group-title">Âm thanh & Kết nối</h4>
        <table class="table-specs-detail">
            <tbody>
                <tr><td>Công nghệ âm thanh</td><td>{{ $sanPham->thongSo->cong_nghe_am_thanh ?? '-' }}</td></tr>
                <tr><td>Cổng giao tiếp</td><td>{{ $sanPham->thongSo->cong_giao_tiep ?? '-' }}</td></tr>
                <tr><td>Kết nối không dây</td><td>{{ $sanPham->thongSo->ket_noi_khong_day ?? '-' }}</td></tr>
                <tr><td>Webcam</td><td>{{ $sanPham->thongSo->webcam ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Nhóm 5: Kích thước & Trọng lượng --}}
    <div class="spec-group-block" id="group-size-weight">
        <h4 class="group-title">Kích thước & Trọng lượng</h4>
        <table class="table-specs-detail">
            <tbody>
                <tr><td>Chất liệu</td><td>{{ $sanPham->thongSo->chat_lieu ?? '-' }}</td></tr>
                <tr><td>Kích thước</td><td>{{ $sanPham->thongSo->kich_thuoc ?? '-' }}</td></tr>
                <tr><td>Trọng lượng</td><td>{{ $sanPham->thongSo->trong_luong ?? '-' }}</td></tr>
                <tr><td>Pin</td><td>{{ $sanPham->thongSo->pin ?? '-' }}</td></tr>
                <tr><td>Hệ điều hành</td><td>{{ $sanPham->thongSo->he_dieu_hanh ?? '-' }}</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Nhóm 6: Tiện ích & Thông số khác --}}
    <div class="spec-group-block" id="group-other">
    <h4 class="group-title">Tiện ích & Thông số khác</h4>
    <table class="table-specs-detail">
        <tbody>
            {{--  CÁC THÔNG SỐ TÙY CHỈNH (Vòng lặp thần thánh) --}}
            @if(!empty($sanPham->thongSo->thong_so_tuy_chinh) && is_array($sanPham->thongSo->thong_so_tuy_chinh))
                @foreach($sanPham->thongSo->thong_so_tuy_chinh as $item)
                    <tr>
                        <td>{{ $item['key'] }}</td>
                        <td>{{ $item['val'] }}</td>
                    </tr>
                @endforeach
            @endif
            
        </tbody>
    </table>
</div>

</div>
    </div>
</div>

</div>

<!---------------- Nhãn giá ------------------>
<div class="product-info">
        <div class="price-box-container">
            
            {{-- CỘT PHẢI: GIÁ STUDENT --}}
            <div class="price-box-right">
                <div>
                    <div class="price-note">Giá dành riêng cho Student</div>
                    <div class="price-main">
                        {{-- Lấy từ uu_dai_student. Nếu chưa nhập thì lấy Giá Bán - 500k --}}
                        {{ number_format((float)($sanPham->uu_dai_student ?? ($sanPham->gia_khuyen_mai - 500000)), 0, ',', '.') }}đ
                    </div>
                    <div class="price-old">
                        {{-- Giá gốc gạch ngang --}}
                        {{ number_format($sanPham->gia_ban, 0, ',', '.') }}đ
                    </div>
                </div>
            </div>

            <div class="devide-price-label">
                <div class="divide-top"></div>
                <p>hoặc</p>
                <div class="divide-bottom"></div>
            </div>

            {{-- CỘT TRÁI: THU CŨ LÊN ĐỜI --}}
            <div class="price-box-left">
                <div>
                    <div class="old-label">Thu cũ lên đời chỉ từ</div>
                    <div class="price-main">
                        {{-- Lấy từ gia_thu_cu. Nếu chưa nhập thì lấy Giá Bán - 2 triệu --}}
                        {{ number_format((float)($sanPham->gia_thu_cu ?? ($sanPham->gia_khuyen_mai - 2000000)), 0, ',', '.') }}đ
                    </div>
                    <div class="price-subsidy">
                        {{-- Lấy trợ giá --}}
                        Trợ giá đến {{ number_format((float)($sanPham->tro_gia ?? 1000000), 0, ',', '.') }}đ
                    </div>
                    <div class="see-price-now">
                        <a href="#">
                            <span class="text-link-11">Định giá tại đây</span>
                            <i class="ri-arrow-down-double-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

<!-- Chọn phiên bản và màu sắc -->
       <div class="section">
    <h3>Phiên bản</h3>
    <div class="options version-group">
        
        <div class="option active" data-name="8CPU 8GPU 16GB 256GB">
            <span>8CPU - 8GPU </span>
            <span>16GB - 256GB</span>
        </div>
        
        <div class="option" data-name="8CPU 8GPU 8GB 256GB">
            <span>8CPU - 8GPU</span>
            <span>8GB - 256GB</span>
        </div>

        <div class="option-custom">
            <span>Cấu hình</span>
            <span>tùy chỉnh</span>
        </div>
    </div>
</div>

<div class="section">
    <h3>Màu sắc</h3>
    <div class="options color-group">
        
        {{-- 1. HIỂN THỊ MÀU MẶC ĐỊNH (Lấy từ bảng san_pham gốc) --}}
        {{-- Luôn để class 'active' cho màu đầu tiên --}}
        <div class="option active" 
             data-name="Tiêu chuẩn" 
             data-price="{{ $sanPham->gia_khuyen_mai }}" 
             data-old-price="{{ $sanPham->gia_ban }}"
             data-img="{{ asset($sanPham->anh_dai_dien) }}">
            
            <div class="option-content">
                <img src="{{ asset($sanPham->anh_dai_dien) }}" alt="Mặc định">
                <div class="option-text">
                    <span class="color">Tiêu chuẩn</span>
                    <span class="price">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>

        {{-- 2. VÒNG LẶP CÁC BIẾN THỂ MÀU (Lấy từ bảng bien_the_san_pham) --}}
        @if(isset($sanPham->bienThe) && $sanPham->bienThe->count() > 0)
            @foreach($sanPham->bienThe as $variant)
                <div class="option" 
                     data-name="{{ $variant->ten_mau }}" 
                     data-price="{{ $variant->gia_ban }}" 
                     {{-- Với biến thể, ta vẫn lấy Giá Gốc của sản phẩm chính để làm mốc so sánh giảm giá --}}
                     data-old-price="{{ $sanPham->gia_ban }}" 
                     data-img="{{ asset($variant->anh_mau) }}">
                     
                    <div class="option-content">
                        <img src="{{ asset($variant->anh_mau) }}" alt="{{ $variant->ten_mau }}">
                        <div class="option-text">
                            <span class="color">{{ $variant->ten_mau }}</span>
                            <span class="price">{{ number_format($variant->gia_ban, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
<!----------------- Địa chỉ giao hàng  ------------>
            <div class="shipping-card">
                <div class="shipping-header">
                    <i class="ri-truck-fill"></i>
                    <span>Thông tin vận chuyển</span>
                </div>
                 <div class="shipping-content" id="shipping-address-container">
    <i class="ri-map-pin-line"></i>
    <a href="javascript:void(0)" id="openModalBtn" class="address-link">Chọn địa chỉ giao hàng để nhận ưu đãi</a>
    <span class="badge-new">Mới</span>
</div>
     </div>
   

    <!------------ Nút thêm vào giỏ hàng và mua ngay --------->
       <div class="purchase-actions">
    {{-- THÊM data-id="{{ $product->id }}" VÀO DÒNG DƯỚI ĐÂY --}}
    <button class="btn-action btn-secondary btn-add-cart" id="addToCartBtn" data-id="{{ $product->id }}">
        <i class="ri-shopping-cart-2-line"></i>
        <strong>THÊM VÀO GIỎ</strong>
        <span>Tích điểm, ưu đãi</span>
    </button>

    <button class="btn-action btn-main btn-buy-now" onclick="triggerBuyNow()">
    <strong>MUA NGAY</strong>
    <span>Giao nhanh trong 2 giờ hoặc nhận tại cửa hàng</span>
</button>

<form action="{{ route('order.buyNow') }}" method="POST" id="hiddenBuyNowForm" style="display: none;">
    @csrf
    <input type="hidden" name="id_san_pham" value="{{ $product->id }}">
    <input type="hidden" name="so_luong" value="1" id="hidden_buy_qty">
</form>

    <button class="btn-action btn-secondary btn-installment">
        <strong>TRẢ GÓP 0%</strong>
        <span>Duyệt hồ sơ nhanh</span>
    </button>
</div>

 <!------------ Kiểm tra hàng tại showroom --------------->
<section class="showroom-check">
    <div class="showroom-header">
        <i class="ri-store-2-line"></i>
        <h3>SHOWROOM CÒN HÀNG</h3>
    </div>
    
    <div class="showroom-slider-wrapper">
        <button class="nav-btn prev-btn" id="prevShowroom"><i class="ri-arrow-left-s-line"></i></button>
        
        <div class="showroom-list" id="showroom-list-container">
            </div>
        
        <button class="nav-btn next-btn" id="nextShowroom"><i class="ri-arrow-right-s-line"></i></button>
    </div>
</section> 

<!---tin tức ----->
<div class="news-standalone-box">
    <div class="news-standalone-header">
        <h3>Tin tức sản phẩm</h3>
        <a href="#">Xem tất cả <i class="ri-arrow-right-s-line"></i></a>
    </div>
    
    <div class="news-standalone-list">
        <a href="#" class="news-card-item">
            <div class="news-card-thumb">
                <img src="/asset/img_chi_tiet_san_pham/mac_thumb1.webp" alt="Tin tức">
            </div>
            <div class="news-card-info">
                Săn MSI Modern 14 giá 11.39 triệu dịp Black Friday và loạt laptop văn phòng giảm sâu từ 8 triệu
            </div>
        </a>

        <a href="#" class="news-card-item">
            <div class="news-card-thumb">
                <img src="/asset/img_chi_tiet_san_pham/vn_mac_1_2.webp" alt="Tin tức">
            </div>
            <div class="news-card-info">
                Trên tay Dell Inspiron 5441: Laptop pin trâu, chip ngon nhưng màn hình "Lụi" và Windows ARM
            </div>
        </a>

        <a href="#" class="news-card-item">
            <div class="news-card-thumb">
                <img src="/asset/img_chi_tiet_san_pham/to.webp" alt="Tin tức">
            </div>
            <div class="news-card-info">
                Dell ra mắt phiên bản cập nhật của Inspiron 14 và Inspiron 14 2-in-1, giá từ 18.5 triệu đồng
            </div>
        </a>
        
    </div>
</div>

 </div>

 <!------------------Bảng thông tin vận chuyển----------------->
      <div id="addressModal" class="address-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Chọn địa chỉ giao hàng</h3>
            <span class="close-modal">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="select-row">
                <div class="field">
                    <label>Tỉnh/Thành phố</label>
                    <select id="province"><option value="">Chọn Tỉnh/Thành</option></select>
                </div>
                <div class="field">
                    <label>Quận/Huyện</label>
                    <select id="district"><option value="">Chọn Quận/Huyện</option></select>
                </div>
                <div class="field">
                    <label>Phường/Xã</label>
                    <select id="ward"><option value="">Chọn Phường/Xã</option></select>
                </div>
            </div>
               <p class="note-text">Chuyển tỉnh / thành phố có thể gây thay đổi về giá hoặc số lượng sản phẩm.</p>
            <div class="field full-width">
                <label>Địa chỉ cụ thể</label>
                <input type="text" id="detailAddress" placeholder="Số nhà, tên đường, ...">
            </div>
        </div>
        <div class="modal-footer">
    <div id="error-message" class="error-msg">
        Vui lòng chọn địa chỉ giao hàng để nhận được ưu đãi hấp dẫn
    </div>
    <button id="btnConfirm" class="btn-confirm" disabled>Xác nhận</button>
    </div>
    </div>
</div>
    </div>

    <!-----Bảng----------------->
<div class="product-desc-vip" id="descSection">
    <h2 class="desc-heading">Đặc điểm nổi bật</h2>
    
    <div class="desc-content-wrapper" id="descWrapper">
    <div class="desc-inner-content">
        @if(!empty($sanPham->mo_ta))
            {{-- Hiển thị nội dung HTML từ Editor --}}
            {!! $sanPham->mo_ta !!}
        @else
            <p>Đang cập nhật bài viết đánh giá cho sản phẩm này...</p>
        @endif
    </div>

    <div class="desc-gradient-overlay" id="descOverlay"></div>
</div>

    <div class="desc-btn-container">
        <button class="btn-toggle-desc" id="btnToggleDesc">
            <span class="btn-text">Xem thêm đặc điểm nổi bật</span>
            <span class="icon-toggle"><i class="ri-arrow-down-s-line"></i></span>
        </button>
    </div>
</div>

<!--------sanphamgiong------>
<section class="related-products-section">
    <div class="container">
        <div class="related-header">
            <h3 class="related-title">Có thể bạn cũng thích</h3>
            <div class="related-tabs">
                <div class="tab-link active" onclick="openRelatedTab(event, 'tab-similar')">
                    Sản phẩm tương tự
                </div>
                <div class="tab-link" onclick="openRelatedTab(event, 'tab-used')">
                    Tham khảo hàng cũ
                </div>
            </div>
        </div>

        <div id="tab-similar" class="related-content active">
            
            {{-- Nút Prev: Chỉ hiện khi có > 5 sản phẩm --}}
            @if($relatedProducts->count() > 5)
                <button class="slider-nav-btn prev" onclick="scrollSlider('slider-similar', -1)">
                    <i class="ri-arrow-left-s-line"></i>
                </button>
            @endif
            
            <div id="slider-similar" class="product-slider-track">
                @if($relatedProducts->count() > 0)
                    @foreach($relatedProducts as $product)
                        {{-- Gọi file template con thẻ sản phẩm --}}
                        @include('client.partials.product_card', ['product' => $product])
                    @endforeach
               @else
    <div class="empty-state-box">
        <i class="ri-search-2-line"></i> {{-- Icon kính lúp --}}
        <p>Đang cập nhật sản phẩm tương tự...</p>
    </div>
@endif
            </div>

            {{-- Nút Next: Chỉ hiện khi có > 5 sản phẩm --}}
            @if($relatedProducts->count() > 5)
                <button class="slider-nav-btn next" onclick="scrollSlider('slider-similar', 1)">
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            @endif
        </div>

        <div id="tab-used" class="related-content" style="display: none;">
             
             @if($usedProducts->count() > 5)
                <button class="slider-nav-btn prev" onclick="scrollSlider('slider-used', -1)">
                    <i class="ri-arrow-left-s-line"></i>
                </button>
             @endif
             
             <div id="slider-used" class="product-slider-track">
                @if($usedProducts->count() > 0)
                    @foreach($usedProducts as $product)
                        @include('client.partials.product_card', ['product' => $product])
                    @endforeach
                @else
    <div class="empty-state-box">
        <i class="ri-inbox-archive-line"></i> {{-- Icon hộp rỗng --}}
        <p>Chưa có sản phẩm cũ nào.</p>
    </div>
@endif
             </div>

             @if($usedProducts->count() > 5)
                <button class="slider-nav-btn next" onclick="scrollSlider('slider-used', 1)">
                    <i class="ri-arrow-right-s-line"></i>
                </button>
             @endif
        </div>
    </div>
</section>


<!-----------------danhgia_-------------------->
<section id="reviews-section" class="reviews-vip-container">
    <div class="vip-header">
        <h3>Đánh giá thực tế từ khách hàng</h3>
    </div>

    <div class="rating-dashboard">
        <div class="rating-summary">
            <div class="big-score">{{ round($avgRating, 1) }}/5</div>
            <div class="big-stars">
                @for($i=1; $i<=5; $i++)
                    <i class="{{ $i <= $avgRating ? 'ri-star-fill' : ($i - 0.5 <= $avgRating ? 'ri-star-half-fill' : 'ri-star-line') }}"></i>
                @endfor
            </div>
            <div class="total-text">{{ $totalReviews }} đánh giá</div>
        </div>

        <div class="rating-bars">
            @for($star=5; $star>=1; $star--)
                @php 
                    $percent = $totalReviews > 0 ? (($starCounts[$star] ?? 0) / $totalReviews) * 100 : 0; 
                @endphp
                <div class="bar-item" onclick="filterReviews('{{ $star }}')" title="Xem đánh giá {{ $star }} sao">
                    <span class="star-label">{{ $star }} <i class="ri-star-fill"></i></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $percent }}%"></div>
                    </div>
                    <span class="count-label">{{ $starCounts[$star] ?? 0 }}</span>
                </div>
            @endfor
        </div>

        <div class="rating-action">
            <p>Bạn đã dùng sản phẩm này?</p>
            @if(Auth::check())
                <button onclick="toggleReviewForm()" class="btn-vip-write">
                    <i class="ri-edit-circle-line"></i> Viết đánh giá
                </button>
            @else
                <button class="btn-vip-write btn-open-login">
    <i class="ri-user-follow-line"></i> Đăng nhập để đánh giá
</button>
            @endif
        </div>
    </div>

   @if(Auth::check())
    <div id="write-review-form" class="review-form-vip " style="display: none;">
        <span class="close-form-btn" onclick="toggleReviewForm()" title="Đóng biểu mẫu">
        <i class="ri-close-line"></i>
    </span>
        <form action="{{ route('client.reviews.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Đảm bảo tên biến product_id đúng với controller --}}
            <input type="hidden" name="product_id" value="{{ $sanPhamChinh->id }}">
            
            <div class="star-select-wrapper">
                <span style="font-weight: 600; color: #555; margin-bottom: 5px;">Bạn cảm thấy sản phẩm thế nào?</span>
                
                {{-- SỬA Ở ĐÂY: Thêm thẻ <i> vào trong label --}}
                <div class="star-rating-select">
                    <input type="radio" id="st5" name="rating" value="5" checked hidden>
                    <label for="st5"><i class="ri-star-fill"></i></label>
                    
                    <input type="radio" id="st4" name="rating" value="4" hidden>
                    <label for="st4"><i class="ri-star-fill"></i></label>
                    
                    <input type="radio" id="st3" name="rating" value="3" hidden>
                    <label for="st3"><i class="ri-star-fill"></i></label>
                    
                    <input type="radio" id="st2" name="rating" value="2" hidden>
                    <label for="st2"><i class="ri-star-fill"></i></label>
                    
                    <input type="radio" id="st1" name="rating" value="1" hidden>
                    <label for="st1"><i class="ri-star-fill"></i></label>
                </div>

                <div id="rating-text-label" class="rating-note">Tuyệt vời - Cực kỳ hài lòng</div>
            </div>

            <textarea name="content" class="form-control-vip" rows="4" placeholder="Mời bạn chia sẻ cảm nhận về sản phẩm..." required></textarea>
            
            <div class="upload-btn-wrapper">
                {{-- name="hinh_anh" phải trùng với controller --}}
                <input type="file" name="hinh_anh" id="review-img-input" accept="image/*" hidden onchange="previewImage(this)">
                <label for="review-img-input" class="btn-upload-img">
                    <i class="ri-camera-fill"></i> Thêm ảnh thực tế
                </label>
                
                <div id="img-preview-box" style="display: none; margin-top: 10px;">
                    <div style="position: relative; display: inline-block;">
                        <img id="preview-img" src="" style="height: 80px; border-radius: 5px; border: 1px solid #ddd;">
                        <span onclick="removeImage()" style="position: absolute; top: -8px; right: -8px; background: #d70018; color: white; border-radius: 50%; width: 20px; height: 20px; text-align: center; line-height: 18px; cursor: pointer;">&times;</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-vip-submit">GỬI ĐÁNH GIÁ NGAY</button>
        </form>
    </div>
    @endif

    <div class="review-filters">
        <button class="filter-btn active" onclick="filterReviews('all')">Tất cả ({{ $totalReviews }})</button>
        <button class="filter-btn" onclick="filterReviews('5')">5 Sao ({{ $starCounts[5] ?? 0 }})</button>
        <button class="filter-btn" onclick="filterReviews('4')">4 Sao ({{ $starCounts[4] ?? 0 }})</button>
        <button class="filter-btn" onclick="filterReviews('3')">3 Sao ({{ $starCounts[3] ?? 0 }})</button>
        <button class="filter-btn" onclick="filterReviews('2')">2 Sao ({{ $starCounts[2] ?? 0 }})</button>
        <button class="filter-btn" onclick="filterReviews('1')">1 Sao ({{ $starCounts[1] ?? 0 }})</button>
        <button class="filter-btn" onclick="filterReviews('has-img')">Có hình ảnh ({{ $reviews->whereNotNull('hinh_anh')->count() }})</button>
    </div>

    <div class="review-list-container">
        @if(count($reviews) > 0)
            @foreach($reviews as $rv)
            <div class="review-card item-review" data-star="{{ $rv->so_sao }}" data-has-img="{{ $rv->hinh_anh ? '1' : '0' }}">
                
                <div class="review-left-col">
                    <div class="user-info-row">
                        <div class="avatar-circle">
                            @if($rv->user && $rv->user->anh_dai_dien)
                                <img src="{{ asset($rv->user->anh_dai_dien) }}" class="img-fit">
                            @else
                                {{ strtoupper(substr($rv->user->ho_ten ?? 'K', 0, 1)) }}
                            @endif
                        </div>
                        <div class="author-info">
                            <div class="author-name">{{ $rv->user->ho_ten ?? 'Khách hàng' }}</div>
                        </div>
                    </div>
                    <div class="verify-buy"><i class="ri-checkbox-circle-fill"></i> Đã mua tại LaptopTF</div>
                    <div class="review-time"><i class="ri-time-line"></i> {{ $rv->created_at->format('H:i - d/m/Y') }}</div>
                </div>

                <div class="review-right-col">
                    <div class="rating-stars-show">
                        @for($s=1; $s<=5; $s++)
                            <i class="{{ $s <= $rv->so_sao ? 'ri-star-fill' : 'ri-star-line' }}"></i>
                        @endfor
                        <span class="rating-label-show">
                            {{ match($rv->so_sao) {
                                5 => 'Tuyệt vời',
                                4 => 'Hài lòng',
                                3 => 'Bình thường',
                                2 => 'Không hài lòng',
                                1 => 'Rất tệ',
                                default => ''
                            } }}
                        </span>
                    </div>

                    <div class="review-content">{{ $rv->noi_dung }}</div>

                    @if($rv->hinh_anh)
                    <div class="review-imgs-list">
                        <img src="{{ asset($rv->hinh_anh) }}" onclick="window.open(this.src)" alt="Review Image">
                    </div>
                    @endif

                    @if(Auth::check() && Auth::id() == $rv->id_nguoi_dung)
                        <form id="delete-form-{{ $rv->id }}" action="{{ route('client.reviews.destroy', $rv->id) }}" method="POST" style="display:inline;">
    @csrf @method('DELETE')
    
    {{-- Thay type="submit" thành type="button" và bỏ onsubmit ở thẻ form đi --}}
    <button type="button" onclick="openDeleteModal('{{ $rv->id }}')" class="btn-delete-rv">
        <i class="ri-delete-bin-line"></i> Xóa đánh giá
    </button>
</form>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 40px; color: #888;">
                <i class="ri-chat-1-line" style="font-size: 40px; color: #eee; display:block; margin-bottom:10px;"></i>
                Chưa có đánh giá nào. Hãy là người đầu tiên!
            </div>
        @endif
    </div>

    <div class="load-more-box">
        <button id="btn-load-more" onclick="loadMoreReviews()" class="btn-vip-outline" style="display: none;">Xem thêm đánh giá</button>
        <button id="btn-collapse" onclick="collapseReviews()" class="btn-vip-outline" style="display: none;">Thu gọn</button>
    </div>


    
    {{-- 1. Khung chứa thông báo (Toast) --}}
    <div class="vip-toast-container" id="toast-container"></div>

    {{-- 2. Modal Xác nhận xóa (Ẩn mặc định) --}}
    <div id="delete-confirm-modal" class="confirm-modal-overlay">
        <div class="confirm-box">
            <div class="icon-box-modal">
                <i class="ri-delete-bin-2-line"></i>
            </div>
            <div class="confirm-title">Xác nhận xóa?</div>
            <p class="confirm-desc">Bạn có chắc chắn muốn xóa đánh giá này không?<br>Hành động này không thể hoàn tác.</p>
            <div class="confirm-actions">
                <button type="button" onclick="closeDeleteModal()" class="btn-confirm-cancel">Hủy bỏ</button>
                <button type="button" id="btn-confirm-delete-action" class="btn-confirm-delete">Xóa ngay</button>
            </div>
        </div>
    </div>
</section>



  </div>

</section>



<!---loading thương hiệu------>
<div id="ltf-loading-overlay" class="loading-overlay">
    <div class="loading-box">
        <div class="spinner-ring"></div>
        <img src="/asset/img/logotf.png" alt="LaptopTF" class="spinner-logo">
    </div>
</div>


<!-------------------STICKY BOTTOM BAR---------------------------->
{{-- STICKY COMPACT BAR (Dính đáy - Nhỏ gọn) --}}
<div id="sticky-compact-bar" class="sticky-compact-bar">
    <div class="compact-container">
        {{-- Phần 1: Ảnh & Tên (Bên trái) --}}
        <div class="compact-left">
            <div class="compact-img">
                <img id="sticky-img" src="/asset/img_chi_tiet_san_pham/vn_mac_1_2.webp" alt="Product">
            </div>
            <div class="compact-info">
                <div class="compact-name">{{ $sanPhamChinh->ten_san_pham ?? 'MacBook Air M2 2024' }}</div>
                <div class="compact-variant" id="sticky-variant-txt">Mặc định</div>
            </div>
        </div>

        {{-- Phần 2: Giá & Nút (Bên phải) --}}
        <div class="compact-right">
            <div class="compact-price-box">
                <div class="compact-price-new" id="sticky-price-main">19.190.000đ</div>
                <div class="compact-price-old" id="sticky-price-old">24.990.000đ</div>
            </div>
            
            <div class="compact-actions">
                {{-- Nút Giỏ hàng (Icon tròn) --}}
               {{-- Nút Giỏ hàng trên Sticky Bar --}}
<button class="btn-compact-icon" id="stickyBtnCart" data-id="{{ $product->id }}" title="Thêm vào giỏ">
    <i class="ri-shopping-cart-2-line"></i>
</button>
                
                {{-- Nút Mua ngay (Gradient đỏ) --}}
              <form action="{{ route('order.buyNow') }}" method="POST" id="formBuyNowSticky" style="display:contents;">
    @csrf
    <input type="hidden" name="id_san_pham" value="{{ $product->id }}">
    <input type="hidden" name="so_luong" value="1" class="buy-now-qty">
    <button type="button" class="btn-compact-buy" id="stickyBtnBuy" onclick="handleBuyNow('formBuyNowSticky')">
        MUA NGAY
    </button>
</form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    
    {{-- Logic thêm giỏ hàng (ĐÃ SỬA: CHECK LOGIN CHUẨN) --}}
    <script>
        document.getElementById('addToCartBtn').onclick = function(e) {
            e.preventDefault();
            
            // --- 1. KIỂM TRA ĐĂNG NHẬP ---
            const loginFlag = document.getElementById('login-flag');
            const isLoggedIn = loginFlag && loginFlag.value === '1';

            // --- 2. NẾU CHƯA ĐĂNG NHẬP -> MỞ MODAL ---
            if (!isLoggedIn) {
                // Gọi hàm mở Modal từ Master Layout
                if(typeof window.globalOpenLogin === 'function') {
                    window.globalOpenLogin();
                } else if(typeof window.openLoginModal === 'function') {
                    window.openLoginModal();
                } else {
                    alert('Vui lòng đăng nhập để mua hàng!');
                }
                return; // Dừng lại, không gửi request
            }

            // --- 3. NẾU ĐÃ ĐĂNG NHẬP -> GỬI REQUEST AXIOS ---
            const productId = "{{ $product->id }}"; 

            axios.post("{{ route('cart.add') }}", { product_id: productId })
            .then(res => {
                if (res.data.status === 'success') {
                    const badge = document.getElementById('cart-badge-pc');
                    const badgeMobile = document.querySelector('.cart-badge-mobile');
                    if(badge) badge.innerText = res.data.total_count;
                    if(badgeMobile) badgeMobile.innerText = res.data.total_count;
                    
                    if(typeof window.showToast === 'function') {
                        window.showToast('Thành công', res.data.message, 'success');
                    } else {
                        alert(res.data.message);
                    }
                }
            })
            .catch(err => {
                console.error("Lỗi:", err);
                alert("Có lỗi xảy ra, vui lòng thử lại!");
            });
        };
    </script>

    <script src="{{ asset('asset/js/product_details.js') }}"></script>
    
  

    <script>
        // --- 1. KHAI BÁO BIẾN TOÀN CỤC (GLOBAL) ---
        var deleteFormId = null;

        // --- 2. ĐỊNH NGHĨA HÀM MỞ/ĐÓNG MODAL ---
        // Phải gán vào window để HTML onclick="..." nhìn thấy được
        window.openDeleteModal = function(reviewId) {
            deleteFormId = 'delete-form-' + reviewId;
            var modal = document.getElementById('delete-confirm-modal');
            if(modal) {
                modal.style.display = 'flex';
            } else {
                console.error("Lỗi: Không tìm thấy ID 'delete-confirm-modal'");
            }
        };

        window.closeDeleteModal = function() {
            var modal = document.getElementById('delete-confirm-modal');
            if(modal) modal.style.display = 'none';
            deleteFormId = null;
        };

        // --- 3. LOGIC KHI DOM ĐÃ LOAD XONG ---
        document.addEventListener("DOMContentLoaded", function() {
            
            // Xử lý nút "Xóa ngay" màu đỏ trong Modal
            var btnDeleteAction = document.getElementById('btn-confirm-delete-action');
            if (btnDeleteAction) {
                btnDeleteAction.addEventListener('click', function() {
                    if (deleteFormId) {
                        var form = document.getElementById(deleteFormId);
                        if (form) {
                            form.submit(); // Gửi lệnh xóa
                        } else {
                            alert("Lỗi: Không tìm thấy Form có ID: " + deleteFormId);
                        }
                    }
                });
            }

            // Đóng modal khi click ra vùng đen
            var modalOverlay = document.getElementById('delete-confirm-modal');
            if (modalOverlay) {
                modalOverlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        window.closeDeleteModal();
                    }
                });
            }

            // Hiển thị thông báo Toast từ Session (Laravel)
            @if(session('success'))
                if(typeof window.showToast === 'function') {
                    window.showToast('Thành công!', "{{ session('success') }}", 'success');
                }
            @endif

            @if(session('error'))
                if(typeof window.showToast === 'function') {
                    window.showToast('Thất bại!', "{{ session('error') }}", 'error');
                }
            @endif
        });
    </script>
@endsection
@push('modal_stack')
    @include('layout.modal_auth')    @include('layout.modal_product') @endpush