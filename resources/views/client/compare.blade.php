@extends('layout.master') {{-- Kế thừa layout chính của bạn --}}

@section('content')

<style>
    /* --- CSS RIÊNG CHO TRANG SO SÁNH --- */
    .compare-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 15px;
        background: #fff;
    }
    .compare-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }
    
    /* Bảng so sánh */
    .table-compare {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* Cố định chiều rộng cột */
    }
    
    .table-compare th, .table-compare td {
        border: 1px solid #eee;
        padding: 15px;
        text-align: center;
        vertical-align: middle;
    }

    /* Cột tiêu đề bên trái (Thông số) */
    .table-compare th.criteria-col {
        width: 150px;
        background: #f9f9f9;
        font-weight: bold;
        text-align: left;
        color: #555;
    }

    /* Ảnh sản phẩm */
    .comp-img {
        width: 100%;
        max-width: 180px;
        height: auto;
        object-fit: contain;
        display: block;
        margin: 0 auto 10px;
    }

    /* Tên sản phẩm */
    .comp-name {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        display: block;
        margin-bottom: 5px;
        text-decoration: none;
        line-height: 1.4;
        height: 45px; /* Giới hạn chiều cao tên */
        overflow: hidden;
    }
    .comp-name:hover { color: #d70018; }

    /* Giá */
    .comp-price {
        color: #d70018;
        font-weight: bold;
        font-size: 18px;
        display: block;
        margin-bottom: 10px;
    }

    /* Nút xóa */
    .btn-remove-compare {
        font-size: 13px;
        color: #999;
        cursor: pointer;
        background: #f0f0f0;
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        transition: 0.3s;
    }
    .btn-remove-compare:hover {
        background: #d70018;
        color: #fff;
    }

    /* Responsive mobile: Cho phép cuộn ngang */
    @media (max-width: 768px) {
        .compare-wrapper {
            overflow-x: auto;
        }
        .table-compare {
            min-width: 800px; /* Đảm bảo bảng không bị co quá nhỏ */
        }
    }
</style>

<div class="compare-container">
    <h1 class="compare-title">So Sánh Sản Phẩm</h1>

    @if($products->count() > 0)
    <div class="compare-wrapper">
        <table class="table-compare">
            {{-- 1. HÀNG SẢN PHẨM (ẢNH + TÊN + GIÁ + NÚT XÓA) --}}
            <thead>
                <tr>
                    <th class="criteria-col">Sản phẩm</th>
                    @foreach($products as $p)
                    <td>
                        {{-- Nút xóa: Gọi hàm JS bên dưới --}}
                        <div style="text-align: right; margin-bottom: 5px;">
                            <button class="btn-remove-compare" onclick="removeProduct({{ $p->id }})">
                                <i class="ri-close-circle-line"></i> Xóa
                            </button>
                        </div>

                        {{-- Ảnh --}}
                        <a href="{{ route('client.product.detail', $p->slug) }}">
                            <img src="{{ asset($p->hinh_anh) }}" alt="{{ $p->ten_san_pham }}" class="comp-img">
                        </a>

                        {{-- Tên --}}
                        <a href="{{ route('client.product.detail', $p->slug) }}" class="comp-name">
                            {{ $p->ten_san_pham }}
                        </a>

                        {{-- Giá --}}
                        <span class="comp-price">
                            {{ number_format($p->gia_khuyen_mai > 0 ? $p->gia_khuyen_mai : $p->gia_ban, 0, ',', '.') }}đ
                        </span>

                        {{-- Nút mua --}}
                        <a href="{{ route('client.product.detail', $p->slug) }}" class="btn-main" style="padding: 5px 15px; font-size: 13px;">
                            Xem chi tiết
                        </a>
                    </td>
                    @endforeach
                    
                    {{-- Nếu so sánh ít hơn 3 sản phẩm, hiển thị cột trống --}}
                    @for($i = $products->count(); $i < 3; $i++)
                    <td style="background: #fafafa; color: #ccc;">
                        <div style="padding: 40px 0;">
                            <i class="ri-add-circle-line" style="font-size: 40px;"></i>
                            <p>Thêm sản phẩm</p>
                            <a href="/" style="color: #007bff; text-decoration: underline;">Về trang chủ</a>
                        </div>
                    </td>
                    @endfor
                </tr>
            </thead>

            {{-- 2. CÁC HÀNG THÔNG SỐ (BODY) --}}
           <tbody>
                {{-- 1. CPU --}}
                <tr>
                    <th class="bg-light text-start px-3">Vi xử lý (CPU)</th>
                    @foreach($products as $p)
                        {{-- Dùng $p->thongSo->ten_cot để lấy dữ liệu --}}
                        <td>{{ $p->thongSo->cpu ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 2. RAM --}}
                <tr>
                    <th class="bg-light text-start px-3">RAM</th>
                    @foreach($products as $p)
                        <td>{{ $p->thongSo->ram ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 3. Ổ cứng --}}
                <tr>
                    <th class="bg-light text-start px-3">Ổ cứng (SSD/HDD)</th>
                    @foreach($products as $p)
                        <td>{{ $p->thongSo->o_cung ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 4. Card đồ họa --}}
                <tr>
                    <th class="bg-light text-start px-3">Card đồ họa (VGA)</th>
                    @foreach($products as $p)
                        <td>{{ $p->thongSo->gpu ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 5. Màn hình --}}
                <tr>
                    <th class="bg-light text-start px-3">Màn hình</th>
                    @foreach($products as $p)
                        <td>{{ $p->thongSo->man_hinh ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 6. Pin --}}
                <tr>
                    <th class="bg-light text-start px-3">Dung lượng Pin</th>
                    @foreach($products as $p)
                        <td>{{ $p->thongSo->pin ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 7. Trọng lượng --}}
                <tr>
                    <th class="bg-light text-start px-3">Trọng lượng</th>
                    @foreach($products as $p)
                        <td>{{ $p->thongSo->trong_luong ?? 'Đang cập nhật' }}</td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>

                {{-- 8. Thông tin khác --}}
                <tr>
                    <th class="bg-light text-start px-3">Tính năng khác</th>
                    @foreach($products as $p)
                        <td class="small text-muted">
                            {!! $p->thongSo->thong_tin_khac ?? '' !!}
                        </td>
                    @endforeach
                    @for($i = $products->count(); $i < 3; $i++) <td></td> @endfor
                </tr>
            </tbody>
        </table>
    </div>
    @else
        <div style="text-align: center; padding: 50px;">
            <p>Không tìm thấy sản phẩm nào để so sánh.</p>
            <a href="/" class="btn-main">Quay lại trang chủ</a>
        </div>
    @endif
</div>

{{-- SCRIPT XỬ LÝ XÓA SẢN PHẨM KHỎI BẢNG SO SÁNH --}}
<script>
    function removeProduct(idToRemove) {
        // 1. Lấy danh sách hiện tại từ LocalStorage
        let compareList = JSON.parse(localStorage.getItem('compare_list')) || [];

        // 2. Lọc bỏ ID cần xóa (chuyển về string để so sánh cho chắc)
        compareList = compareList.filter(id => id.toString() !== idToRemove.toString());

        // 3. Lưu lại vào LocalStorage
        localStorage.setItem('compare_list', JSON.stringify(compareList));

        // 4. Load lại trang với danh sách ID mới
        if (compareList.length > 0) {
            window.location.href = `/so-sanh?ids=${compareList.join(',')}`;
        } else {
            // Nếu xóa hết thì về trang chủ
            alert("Đã xóa hết sản phẩm so sánh!");
            window.location.href = '/';
        }
    }
</script>

@endsection