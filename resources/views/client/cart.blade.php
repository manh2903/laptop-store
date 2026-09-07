@extends('layout.master')

@section('title', 'Giỏ hàng của tôi - LaptopTF')

@section('css')
<style>
    /* CSS RIÊNG CHO TRANG GIỎ HÀNG - KHÔNG DÙNG BOOTSTRAP */
    :root {
        --primary-red: #d70018;
        --bg-body: #f4f6f8;
        --text-color: #333;
        --border-color: #e0e0e0;
    }

    .cart-page-body {
        background-color: var(--bg-body);
        padding: 30px 0;
        min-height: 80vh;
        font-family: 'Inter', sans-serif;
    }

    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .cart-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
    }

    /* Layout chính: Chia 2 cột */
    .cart-flex {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .cart-main { flex: 1.5; } /* Cột trái rộng hơn */
    .cart-sidebar { flex: 0.8; } /* Cột phải */

    /* Thẻ trắng bọc nội dung */
    .white-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    /* Từng item sản phẩm */
    .cart-item {
        display: flex;
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        position: relative;
    }

    .cart-item:last-child { border-bottom: none; }

    .item-img-box {
        width: 110px;
        height: 110px;
        margin-right: 20px;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        padding: 5px;
    }

    .item-img-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .item-info { flex: 1; }

    .item-name {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 8px;
        display: block;
    }

    .item-price {
        color: var(--primary-red);
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 15px;
    }

    /* Nút tăng giảm số lượng */
    .qty-box {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        width: fit-content;
    }

    .btn-qty {
        width: 32px;
        height: 32px;
        border: none;
        background: #f9f9f9;
        cursor: pointer;
        font-size: 16px;
        transition: 0.2s;
    }
    .btn-qty:hover { background: #eee; }

    .input-qty {
        width: 40px;
        height: 32px;
        text-align: center;
        border: none;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        font-weight: 600;
    }

    /* Cột giá tổng & nút xóa */
    .item-right {
        text-align: right;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .btn-remove {
        background: none;
        border: none;
        color: #999;
        font-size: 20px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-remove:hover { color: var(--primary-red); }

    .item-subtotal {
        font-weight: 700;
        font-size: 16px;
        color: #333;
    }

    /* Sidebar tóm tắt đơn hàng */
    .summary-card { padding: 25px; }

    .summary-header {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 14px;
        color: #666;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f9f9f9;
    }

    .total-label { font-weight: 700; color: #333; font-size: 16px; }
    .total-val { font-weight: 700; color: var(--primary-red); font-size: 22px; }

    .btn-order {
        width: 100%;
        background-color: var(--primary-red);
        color: #fff;
        border: none;
        padding: 15px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 16px;
        margin-top: 25px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(215, 0, 24, 0.2);
        transition: 0.3s;
    }
    .btn-order:hover { background-color: #b50014; transform: translateY(-2px); }

    .back-home {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 20px;
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
    }

    /* Giỏ hàng trống */
    .empty-cart-state {
        text-align: center;
        background: #fff;
        padding: 60px;
        border-radius: 12px;
    }

    .empty-cart-state i { font-size: 80px; color: #ddd; margin-bottom: 20px; display: block; }

    @media (max-width: 992px) {
        .cart-flex { flex-direction: column; }
        .cart-sidebar { width: 100%; flex: none; }
    }
</style>
@endsection

@section('content')
<div class="cart-page-body">
    <div class="cart-container">
        <h1 class="cart-title"><i class="ri-shopping-cart-line"></i> Giỏ hàng của tôi</h1>

        @if(count($items) > 0)
        <div class="cart-flex">
            <div class="cart-main">
                <div class="white-card">
                    @foreach($items as $item)
                    <div class="cart-item">
                        <div class="item-img-box">
                            <img src="{{ asset($item->sanPham->anh_dai_dien) }}" alt="laptop">
                        </div>

                        <div class="item-info">
                            <a href="#" class="item-name">{{ $item->sanPham->ten_san_pham }}</a>
                            <p style="font-size: 12px; color: #999; margin-bottom: 8px;">Phiên bản: Tiêu chuẩn</p>
                            <div class="item-price">
                                {{ number_format($item->sanPham->gia_khuyen_mai > 0 ? $item->sanPham->gia_khuyen_mai : $item->sanPham->gia_ban, 0, ',', '.') }}₫
                            </div>

                            <div class="qty-box">
                                <button class="btn-qty" onclick="updateQty({{ $item->id }}, -1)">-</button>
                                <input type="text" class="input-qty" value="{{ $item->so_luong }}" readonly>
                                <button class="btn-qty" onclick="updateQty({{ $item->id }}, 1)">+</button>
                            </div>
                        </div>

                        <div class="item-right">
                            <button class="btn-remove" title="Xóa sản phẩm" onclick="deleteItem({{ $item->id }})">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <div class="item-subtotal">
                                {{ number_format(($item->sanPham->gia_khuyen_mai > 0 ? $item->sanPham->gia_khuyen_mai : $item->sanPham->gia_ban) * $item->so_luong, 0, ',', '.') }}₫
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <a href="/" class="back-home">
                    <i class="ri-arrow-left-s-line"></i> Tiếp tục mua sắm
                </a>
            </div>

            <div class="cart-sidebar">
                <div class="white-card summary-card">
                    <div class="summary-header">Tóm tắt đơn hàng</div>
                    
                    <div class="summary-line">
                        <span>Tạm tính ({{ count($items) }} món)</span>
                        <span style="font-weight: 600; color: #333;">{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                    </div>
                    
                    <div class="summary-line">
                        <span>Phí vận chuyển</span>
                        <span style="color: #28a745; font-weight: 600;">Miễn phí</span>
                    </div>

                    <div class="summary-total">
                        <span class="total-label">Tổng tiền</span>
                        <span class="total-val">{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                    </div>

                    <button class="btn-order">TIẾN HÀNH ĐẶT HÀNG</button>
                    
                    <p style="font-size: 12px; color: #999; text-align: center; margin-top: 15px; line-height: 1.4;">
                        Hỗ trợ thanh toán qua thẻ tín dụng, chuyển khoản hoặc tiền mặt khi nhận hàng.
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="empty-cart-state">
            <i class="ri-shopping-basket-line"></i>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p style="color: #999; margin-bottom: 25px;">Hãy lấp đầy giỏ hàng bằng những sản phẩm laptop cực hời nhé!</p>
            <a href="/" style="display: inline-block; background: var(--primary-red); color: #fff; padding: 12px 35px; border-radius: 30px; text-decoration: none; font-weight: 700;">QUAY LẠI TRANG CHỦ</a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
<script>
    // Hàm cập nhật số lượng
    function updateQty(itemId, change) {
        let btn = window.event.currentTarget;
        let container = btn.parentElement;
        let input = container.querySelector('.input-qty');
        
        let currentQty = parseInt(input.value);
        let newQty = currentQty + change;

        if (newQty < 1) return;

        input.value = "...";

        axios.post('{{ route("cart.update") }}', {
            item_id: itemId,
            quantity: newQty,
            _token: '{{ csrf_token() }}'
        })
        .then(res => {
            if (res.data.status === 'success') {
                location.reload(); 
            }
        })
        .catch(err => {
            alert("Không thể cập nhật số lượng");
            location.reload();
        });
    }

    // Hàm xóa sản phẩm
    function deleteItem(itemId) {
        if (typeof Swal === 'undefined') {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
                executeDelete(itemId);
            }
            return;
        }

        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Sản phẩm sẽ được loại khỏi giỏ hàng của bạn.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d70018',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                executeDelete(itemId);
            }
        });
    }

    function executeDelete(itemId) {
        axios.post('{{ route("cart.remove") }}', {
            item_id: itemId,
            _token: '{{ csrf_token() }}'
        })
        .then(res => {
            if (res.data.status === 'success') {
                if(typeof window.showToast === 'function') {
                    window.showToast("Thành công", "Đã xóa sản phẩm", "success");
                }
                setTimeout(() => { location.reload(); }, 800);
            }
        })
        .catch(err => {
            alert("Lỗi hệ thống, vui lòng thử lại.");
        });
    }
</script>
@endsection