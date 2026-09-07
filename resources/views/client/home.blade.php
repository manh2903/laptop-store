   <!--Đầu trang-->
   @extends('layout.master') @section('title', 'Trang chủ - LaptopTF')

    @section('content')

    <!--Phần thân-->
    <section class="main-menu">
    <div class="conmymenu">
        <div class="row-menu">
            <div class="nav-menu">
                <nav class="main-menu-bar">
                    <ul class="menu-list">
                        @foreach($mainMenu as $menuItem)
                            <li class="{{ $menuItem->childrenActive->count() > 0 ? 'has-mega-menu' : '' }}">
                                <a href="#">
                                    @if($menuItem->icon) <i class="{{ $menuItem->icon }}"></i> @endif
                                    <span>{{ $menuItem->ten_danh_muc }}</span>
                                </a>

                                {{-- Mega Menu Dropdown --}}
                                @if($menuItem->childrenActive->count() > 0)
                                    <div class="mega-dropdown">
                                        <div class="mega-container">
                                            @foreach($menuItem->childrenActive as $column)
                                                <div class="mega-col">
                                                    <h3 class="mega-title">{{ $column->ten_danh_muc }}</h3>
                                                    <ul>
                                                        @foreach($column->childrenActive as $subLink)
                                                            <li>
                                                                <a href="{{ route('product.category', $subLink->slug) }}">
                                                                    {{ $subLink->ten_danh_muc }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

    <!------------------------PHẦN BANNER-SLIDER------------------------------------>
    <section class="banner-section">
    <div class="conmymenu"> 
        <div class="banner-grid">    
            <div class="banner-left">
                <div class="main-slider">
                    <div class="slides-inner">
                        <img class="slide" src="asset/img/silder1.png" alt="Slide 1">
                        <img class="slide" src="asset/img/silder2.png" alt="Slide 2">
                        <img class="slide" src="asset/img/silder3.png" alt="Slide 3">
                    </div>
                    <div class="slider-dots">
                        <span class="dot active" onclick="currentSlide(0)"></span>
                        <span class="dot" onclick="currentSlide(1)"></span>
                        <span class="dot" onclick="currentSlide(2)"></span>
                    </div>
                </div>
            </div>
            <div class="banner-right">
                <a href="#" class="sub-banner"><img src="asset/img/slider4.png" alt="Hướng dẫn mua hàng"></a>
                <a href="#" class="sub-banner"><img src="asset/img/slider5.png" alt="Nhận xét"></a>
            </div>
        </div>
    </div>
</section>

<section id="brand-filter-section">
    <h3>Thương hiệu nổi bật</h3> {{-- Đổi tiêu đề chút cho oách --}}
    
    <div class="brand-list">
        {{-- SỬA: Duyệt mảng $brands lấy từ Controller --}}
        @foreach($brands as $brand)
            {{-- Link tạm thời để #, sau này bạn làm trang lọc sản phẩm theo hãng thì điền route vào sau --}}
            <a href="#" class="brand-item">
                
                @if($brand->hinh_anh)
                    {{-- QUAN TRỌNG: Code AdminBrandController mình gửi lưu ảnh có sẵn chữ 'storage/' rồi
                         Nên ở đây chỉ cần asset($brand->hinh_anh) là đủ. 
                         Không cần nối chuỗi 'storage/' thủ công nữa --}}
                    <img src="{{ asset($brand->hinh_anh) }}" alt="{{ $brand->ten_thuong_hieu }}">
                @else
                    {{-- Nếu không có logo thì hiện tên --}}
                    <span class="brand-text">{{ $brand->ten_thuong_hieu }}</span>
                @endif
                
            </a>
        @endforeach
    </div>
</section>

<!----------------------------------NEEDS-SECTION(Nhu cầu)---------------------------------------------------->
<section id="needs-section">
    <h3>Chọn theo nhu cầu</h3>
    <div class="needs-list">
        @foreach($needSection as $need)
            <a href="{{ route('product.category', $need->slug) }}" class="needs-item">
                
                {{-- ✅ QUAN TRỌNG: Thêm div class="img-box" bao quanh ảnh --}}
                <div class="img-box">
                    @if($need->hinh_anh)
                        <img src="{{ asset('storage/' . $need->hinh_anh) }}" alt="{{ $need->ten_danh_muc }}">
                    @else
                        {{-- Nếu không có ảnh thì hiện placeholder hoặc để trống --}}
                        <img src="{{ asset('assets/img/no-image.png') }}" alt="No Image"> 
                    @endif
                </div>

                <span class="label">{{ $need->ten_danh_muc }}</span>
            </a>
        @endforeach
    </div>
</section>
<!-----------------------------------PRODUCTS-------------------------------------------->
<section id="laptop-promo-section">
    <div class="promo-container">
        <div class="promo-header">
            <h2>Tuần Lễ Laptop - Săn Sale Giá Sốc</h2>
            <div style="background: #333; color: yellow; padding: 5px 10px; border-radius: 4px; font-size: 12px;">
                🎁 Tặng Combo Phím Chuột
            </div>
        </div>

        <button class="nav-btn prev"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="nav-btn next"><i class="fa-solid fa-chevron-right"></i></button>

        <div class="product-list">
            @foreach($flashSaleProducts as $product)
                @php
                    $giaBanThuc = $product->gia_khuyen_mai > 0 ? $product->gia_khuyen_mai : $product->gia_ban;
                    $giaGoc     = $product->gia_ban;
                    $phanTramGiam = 0;
                    if($product->gia_khuyen_mai > 0 && $product->gia_khuyen_mai < $product->gia_ban) {
                        $phanTramGiam = round((($product->gia_ban - $product->gia_khuyen_mai) / $product->gia_ban) * 100);
                    }
                @endphp

                <div class="product-card">
                    @if($phanTramGiam > 0)
                        <span class="badge-sale">Giảm {{ $phanTramGiam }}%</span>
                    @endif
                    @if($product->tra_gop_0_phan_tram)
                        <span class="badge-installment">Trả góp 0%</span>
                    @endif

                    <div class="card-image">
                        <img src="{{ Str::startsWith($product->anh_dai_dien, 'http') ? $product->anh_dai_dien : asset($product->anh_dai_dien) }}" 
                             alt="{{ $product->ten_san_pham }}">
                    </div>
                    
                    <div class="product-name">
                        <a href="{{ route('client.product.detail', $product->slug) }}">{{ Str::limit($product->ten_san_pham, 40) }}</a>
                    </div>
                    
                    <div class="price-box">
                        <span class="current-price">{{ number_format($giaBanThuc, 0, ',', '.') }}đ</span>
                        @if($phanTramGiam > 0)
                            <span class="old-price">{{ number_format($giaGoc, 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    
                     <div class="card-footer">
                        @if($product->danh_gia > 0)
                            <div class="rating"><i class="fa-solid fa-star"></i> {{ $product->danh_gia }}</div>
                        @else
                             {{-- Giữ chỗ nếu không có rating --}}
                             <div class="rating"></div>
                        @endif
                        <div class="like-btn"><i class="ri-heart-line"></i> Yêu thích</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

   <!-----------------------------------PRODUCTS-FULL--------------------------------------------> 
  <section id="product-full">
    <div class="conmymenu">
        <div class="sort-header">
            <h3>Sắp xếp theo</h3>
            <div class="sort-buttons">
                <button class="sort-btn"><i class="ri-fire-fill"></i> Phổ biến</button>
                <button class="sort-btn"><i class="ri-discount-percent-fill"></i> Khuyến mãi HOT</button>
                <button class="sort-btn"><i class="ri-arrow-down-s-fill"></i> Giá Thấp - Cao</button>
                <button class="sort-btn"><i class="ri-arrow-up-s-fill"></i> Giá Cao - Thấp</button>
            </div>
        </div>

        <div class="full-product-grid-new">
            @foreach($allProducts as $product)
                @php
                    // Logic giá (Giá gốc vs Giá bán thực)
                    $giaBanThuc = $product->gia_khuyen_mai > 0 ? $product->gia_khuyen_mai : $product->gia_ban;
                    $giaGoc     = $product->gia_ban;
                    
                    $phanTramGiam = 0;
                    if($product->gia_khuyen_mai > 0 && $product->gia_khuyen_mai < $product->gia_ban) {
                        $phanTramGiam = round((($product->gia_ban - $product->gia_khuyen_mai) / $product->gia_ban) * 100);
                    }
                @endphp

                <div class="product-card full-list-card">
                    {{-- Badge Sale --}}
                    @if($phanTramGiam > 0)
                        <span class="badge-sale">Giảm {{ $phanTramGiam }}%</span>
                    @endif
                    
                    {{-- Badge Trả góp --}}
                    @if($product->tra_gop_0_phan_tram)
                        <span class="badge-installment">Trả góp 0%</span>
                    @endif

                    <div class="card-image">
                         <img src="{{ Str::startsWith($product->anh_dai_dien, 'http') ? $product->anh_dai_dien : asset($product->anh_dai_dien) }}" 
                             alt="{{ $product->ten_san_pham }}">
                    </div>
                    
                    <div class="product-name">
                        <a href="{{ route('client.product.detail', $product->slug) }}">{{ Str::limit($product->ten_san_pham, 50) }}</a>
                    </div>
                    
                    <div class="price-section">
                        <span class="current-price">{{ number_format($giaBanThuc, 0, ',', '.') }}đ</span>
                        @if($phanTramGiam > 0)
                            <span class="old-price">{{ number_format($giaGoc, 0, ',', '.') }}đ</span>
                        @endif
                    </div>
                    
                    {{-- PHẦN INFO TEXT ĐÚNG CẤU TRÚC HTML YÊU CẦU --}}
                    <div class="info-text">
                        {{-- 1. Ưu tiên hiện giá Smember trước --}}
                        @if($product->giam_smember > 0)
                            <p class="block-smem-price">
                                Smember giảm đến&nbsp;<span>{{ number_format($product->giam_smember, 0, ',', '.') }}đ</span>
                            </p>
                        {{-- 2. Nếu không có Smember thì check thông tin thêm (Hàng mới về) --}}
                        @elseif($product->thong_tin_them)
                            <p class="product-info">{{ $product->thong_tin_them }}</p>
                        @endif

                       {{-- 3: GIÁ TF-STUDENT (TỰ ĐỘNG TRỪ 500K) --}}
                        <p class="student-discount">
                            Giá TF-Student 
                            <strong>
                                {{-- Lấy giá khuyến mãi - 500.000đ --}}
                                {{ number_format($product->gia_khuyen_mai - 500000, 0, ',', '.') }}đ
                            </strong>
                        </p>
                                    
                        {{-- 4. Coupon / Ưu đãi khác --}}
                        @if($product->uu_dai_khac)
                            <p class="coupon-price">{{ Str::limit($product->uu_dai_khac, 60) }}</p>
                        @else
                            {{-- Placeholder ẩn để giữ khung --}}
                            <p class="coupon-price" style="visibility: hidden">...</p>
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        @if($product->danh_gia > 0)
                            <div class="rating"><i class="fa-solid fa-star"></i> {{ $product->danh_gia }}</div>
                        @else
                             {{-- Giữ chỗ nếu không có rating --}}
                             <div class="rating"></div>
                        @endif
                        <div class="like-btn"><i class="ri-heart-line"></i> Yêu thích</div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Phân trang --}}
        

        
    </div>
</section>



    <!-------------------------Chân trang--------------------------->
@endsection

@push('modal_stack')
    @include('layout.modal_auth')   
@endpush

 
