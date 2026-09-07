
<!--------------------- Lightbox Modal(chi-tiet-san-pham) -------------------->
<div id="lightbox-modal" class="lightbox-overlay">
    <div class="lb-toolbar">
        <div class="lb-counter">
            <span id="lb-current">1</span> / <span id="lb-total">10</span>
        </div>
        <div class="lb-actions">
            <button class="lb-btn" id="btnZoomOut"><i class="ri-zoom-out-line"></i></button>
            <button class="lb-btn" id="btnZoomIn"><i class="ri-zoom-in-line"></i></button>
            <button class="lb-btn lb-close-btn" id="lbClose"><i class="ri-close-line"></i></button>
        </div>
    </div>

    <div class="lb-canvas">
        <img id="lightbox-img" src="" alt="Full Image">
    </div>

    <button class="lb-nav lb-prev" id="lbPrev"><i class="ri-arrow-left-s-line"></i></button>
    <button class="lb-nav lb-next" id="lbNext"><i class="ri-arrow-right-s-line"></i></button>
</div>


<div id="full-specs-modal" class="specs-modal-overlay">
    <div class="specs-modal-container slide-up-animation">
        <div class="specs-modal-header">
            <h3>Thông số kỹ thuật chi tiết</h3>
            <button class="close-specs-modal" id="closeFullSpecsBtn"><i class="ri-close-line"></i></button>
        </div>
        <div class="specs-modal-body">
            <div class="full-specs-content">
                
                <div class="spec-group-modern">
                    <h4><i class="ri-cpu-line"></i> Bộ xử lý & Đồ họa</h4>
                    <table class="specs-table">
                        <tr><td>Chip xử lý (CPU)</td><td>Apple M2 (8 nhân CPU)</td></tr>
                        <tr><td>Chip đồ họa (GPU)</td><td>8 nhân GPU</td></tr>
                        <tr><td>Neural Engine</td><td>16 nhân</td></tr>
                    </table>
                </div>

                <div class="spec-group-modern">
                    <h4><i class="ri-database-2-line"></i> RAM & Lưu trữ</h4>
                    <table class="specs-table">
                        <tr><td>Dung lượng RAM</td><td>16 GB</td></tr>
                        <tr><td>Loại RAM</td><td>LPDDR5 (Onboard - Không nâng cấp)</td></tr>
                        <tr><td>Dung lượng ổ cứng</td><td>256 GB SSD</td></tr>
                    </table>
                </div>

                 <div class="spec-group-modern">
                    <h4><i class="ri-computer-line"></i> Màn hình</h4>
                    <table class="specs-table">
                        <tr><td>Kích thước</td><td>13.6 inch</td></tr>
                        <tr><td>Độ phân giải</td><td>Liquid Retina (2560 x 1664)</td></tr>
                        <tr><td>Công nghệ</td><td>True Tone, Dải màu rộng P3, 500 nits</td></tr>
                    </table>
                </div>
                
                <div class="spec-group-modern">
                    <h4><i class="ri-wifi-line"></i> Giao tiếp & Kết nối</h4>
                    <table class="specs-table">
                        <tr><td>Cổng kết nối</td><td>2 x Thunderbolt / USB 4, Jack 3.5mm, MagSafe 3</td></tr>
                        <tr><td>Wi-Fi</td><td>Wi-Fi 6 (802.11ax)</td></tr>
                        <tr><td>Bluetooth</td><td>Bluetooth 5.3</td></tr>
                    </table>
                </div>

                 <div class="spec-group-modern">
                    <h4><i class="ri-scales-3-line"></i> Thiết kế & Trọng lượng</h4>
                    <table class="specs-table">
                        <tr><td>Kích thước</td><td>Dài 304.1 mm - Rộng 215 mm - Dày 11.3 mm</td></tr>
                        <tr><td>Trọng lượng</td><td>1.24 kg</td></tr>
                        <tr><td>Chất liệu</td><td>Vỏ kim loại nguyên khối</td></tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>


<!---------------- Modal Tùy chỉnh cấu hình MacBook Air M2 ---------------->
<div id="custom-config-modal" class="specs-modal-overlay">
    <div class="specs-modal-container-large custom-mode">
        <div class="specs-modal-header-large">
            <div class="header-left">
                <h3 id="modal-product-name">Nâng cấp cấu hình</h3>
                <p>Tùy chỉnh linh kiện nâng cấp chính hãng</p>
            </div>
            <button class="close-specs-modal" id="closeCustomModal"><i class="ri-close-line"></i></button>
        </div>

        <div class="specs-modal-body-large" id="customModalBody">
    
    {{-- 1. MÀU SẮC MÁY --}}
    <div class="custom-section">
        <label class="custom-label">Màu sắc máy</label>
        <div class="options color-sync-grid">
            {{-- A. Màu Mặc Định --}}
            <div class="option active" 
                 data-type="color"
                 data-name="Mặc định" 
                 data-price="{{ $sanPham->gia_khuyen_mai }}" 
                 data-img="{{ asset($sanPham->anh_dai_dien) }}">
                <div class="option-content">
                    <img src="{{ asset($sanPham->anh_dai_dien) }}">
                    <div class="option-text">
                        <span class="color">Mặc định</span>
                        <span class="price">{{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}đ</span>
                    </div>
                </div>
                {{-- ĐÃ XÓA CHECK-MARK Ở ĐÂY --}}
            </div>

            {{-- B. Các màu biến thể --}}
            @if(isset($sanPham->bienThe))
                @foreach($sanPham->bienThe as $bt)
                    <div class="option" 
                         data-type="color"
                         data-name="{{ $bt->ten_mau }}" 
                         data-price="{{ $bt->gia_ban }}" 
                         data-img="{{ asset($bt->anh_mau) }}">
                        <div class="option-content">
                            <img src="{{ asset($bt->anh_mau) }}">
                            <div class="option-text">
                                <span class="color">{{ $bt->ten_mau }}</span>
                                <span class="price">{{ number_format($bt->gia_ban, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        {{-- ĐÃ XÓA CHECK-MARK Ở ĐÂY --}}
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    @php
        $upgrades = $sanPham->cauHinh->groupBy('loai_cau_hinh');
    @endphp

    {{-- 2. CHIP (CPU) --}}
    <div class="custom-section">
        <label class="custom-label">Chip (Bộ vi xử lý & Đồ họa)</label>
        <div class="auto-grid-layout">
            {{-- Mặc định --}}
            <div class="config-card active" data-price="0" data-name="{{ $sanPham->thongSo->cong_nghe_cpu ?? 'Chip tiêu chuẩn' }}">
                <div class="card-content">
                    <strong>{{ $sanPham->thongSo->cong_nghe_cpu ?? 'Chip tiêu chuẩn' }}</strong>
                    <span class="price-tag">Mặc định</span>
                </div>
            </div>
            {{-- Nâng cấp --}}
            @if(isset($upgrades['chip']))
                @foreach($upgrades['chip'] as $item)
                    <div class="config-card" data-price="{{ $item->gia_them }}" data-name="{{ $item->ten_cau_hinh }}">
                        <div class="card-content">
                            <strong>{{ $item->ten_cau_hinh }}</strong>
                            <span class="price-tag">+ {{ number_format($item->gia_them, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- 3. RAM --}}
    <div class="custom-section">
        <label class="custom-label">Bộ nhớ RAM</label>
        <div class="auto-grid-layout">
            {{-- Mặc định --}}
            <div class="config-card active" data-price="0" data-name="{{ $sanPham->thongSo->ram ?? 'RAM tiêu chuẩn' }}">
                <div class="card-content">
                    <strong>{{ $sanPham->thongSo->ram ?? 'RAM tiêu chuẩn' }}</strong>
                    <span class="price-tag">Mặc định</span>
                </div>
            </div>
            {{-- Nâng cấp --}}
            @if(isset($upgrades['ram']))
                @foreach($upgrades['ram'] as $item)
                    <div class="config-card" data-price="{{ $item->gia_them }}" data-name="{{ $item->ten_cau_hinh }}">
                        <div class="card-content">
                            <strong>{{ $item->ten_cau_hinh }}</strong>
                            <span class="price-tag">+ {{ number_format($item->gia_them, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- 4. Ổ CỨNG SSD --}}
    <div class="custom-section">
        <label class="custom-label">Ổ cứng SSD</label>
        <div class="auto-grid-layout">
            {{-- Mặc định --}}
            <div class="config-card active" data-price="0" data-name="{{ $sanPham->thongSo->o_cung ?? 'SSD tiêu chuẩn' }}">
                <div class="card-content">
                    <strong>{{ $sanPham->thongSo->o_cung ?? 'SSD tiêu chuẩn' }}</strong>
                    <span class="price-tag">Mặc định</span>
                </div>
            </div>
            {{-- Nâng cấp --}}
            @if(isset($upgrades['ssd']))
                @foreach($upgrades['ssd'] as $item)
                    <div class="config-card" data-price="{{ $item->gia_them }}" data-name="{{ $item->ten_cau_hinh }}">
                        <div class="card-content">
                            <strong>{{ $item->ten_cau_hinh }}</strong>
                            <span class="price-tag">+ {{ number_format($item->gia_them, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- 5. ADAPTER (SẠC) --}}
    <div class="custom-section">
        <label class="custom-label">Bộ tiếp hợp nguồn (Adapter)</label>
        <div class="auto-grid-layout">
            <div class="config-card active" data-price="0" data-name="Sạc tiêu chuẩn theo máy">
                <div class="card-content">
                    <strong>Sạc tiêu chuẩn theo máy</strong>
                    <span class="price-tag">Mặc định</span>
                </div>
            </div>
            @if(isset($upgrades['adapter']))
                @foreach($upgrades['adapter'] as $item)
                    <div class="config-card" data-price="{{ $item->gia_them }}" data-name="{{ $item->ten_cau_hinh }}">
                        <div class="card-content">
                            <strong>{{ $item->ten_cau_hinh }}</strong>
                            <span class="price-tag">+ {{ number_format($item->gia_them, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</div>

        <div class="modal-specs-footer custom-footer-layout">
            <div class="footer-left">
                <div class="selection-summary">
                    <p id="summary-text" class="summary-desc">MacBook Air M2 2022 16GB 256GB 2022 Sạc 30W I Chính hãng Apple Việt Nam-Xám</p>
                    
                    <div class="price-display-group">
                        <span class="price-label">Giá:</span>
                        <div class="price-main-wrap">
                            <h2 id="custom-total-price">19.190.000đ</h2>
                            <span id="old-price-display" class="old-price">24.990.000đ</span>
                        </div>
                        <div id="extra-info" style="display: none;">
                            <p class="vat-note">(Đã bao gồm VAT)</p>
                            <p class="delivery-note">Giao dự kiến: 2-3 tuần</p>
                        </div>
                    </div>
                </div>
            </div>
            <button id="btn-submit-modal" class="btn-checkout-modal-red">XÁC NHẬN MUA NGAY</button>
        </div>
    </div>
</div>


<!---------------- Modal Đăng ký nhận tin khi có hàng ---------------->
<div id="ltf-order-modal" class="specs-modal-overlay">
    <div class="order-modal-container">
        <button class="close-order-x" id="ltf-close-x"><i class="ri-close-line"></i></button>

        <div class="order-modal-header">
            <h3>Đăng ký nhận tin khi có hàng</h3>
        </div>

        <form id="ltf-order-form">
            <div class="order-modal-body">
                <div class="input-row">
                    <div class="field">
                        <input type="text" id="ltf-cust-name" placeholder="Họ tên (bắt buộc)">
                    </div>
                    <div class="field">
                        <input type="tel" id="ltf-cust-phone" placeholder="Số điện thoại (bắt buộc)">
                    </div>
                </div>
                
                <div class="field full-width">
                    <input type="email" id="ltf-cust-email" placeholder="Địa chỉ email (để nhận phản hồi)">
                </div>

                <div class="checkbox-field">
                    <input type="checkbox" id="ltf-newsletter" checked>
                    <label for="ltf-newsletter">Đăng ký nhận bản tin khuyến mãi qua email</label>
                </div>
            </div>

            <div class="order-modal-footer">
                <button type="button" class="btn-exit" id="ltf-btn-exit">Thoát</button>
                <button type="submit" class="btn-submit-info">Đăng ký nhận thông tin</button>
            </div>
        </form>
    </div>
</div>

<div id="ltf-success-toast" class="success-toast">
    <div class="toast-content">
        <i class="ri-checkbox-circle-fill"></i>
        <span>Thông tin nâng cấp đã LaptopTF ghi nhận và sẽ liên hệ lại bạn trong vòng 24h</span>
    </div>
</div>