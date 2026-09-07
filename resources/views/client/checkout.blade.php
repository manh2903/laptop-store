@extends('layout.master')

@section('title', 'Xác nhận thanh toán - LaptopTF')

@section('css')
<style>
    :root {
        --primary-color: #d70018;
        --bg-gray: #f4f6f8;
        --text-dark: #333;
        --border-radius: 12px;
    }

    .checkout-wrapper { background-color: var(--bg-gray); padding: 40px 0; min-height: 100vh; font-family: 'Segoe UI', Tahoma, sans-serif; }
    .checkout-container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
    
    .checkout-grid { display: grid; grid-template-columns: 1fr 400px; gap: 25px; align-items: flex-start; }
    
    .checkout-card { background: #fff; border-radius: var(--border-radius); padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
    .card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
    .card-header i { color: var(--primary-color); font-size: 24px; }
    .card-header h2 { font-size: 18px; margin: 0; font-weight: 700; color: var(--text-dark); }

    /* Form Styles */
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #555; }
    .form-input { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: 0.3s; }
    .form-input:focus { border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(215, 0, 24, 0.1); }

    /* Payment Methods */
    .payment-options { display: flex; flex-direction: column; gap: 12px; }
    .payment-label { 
        display: flex; align-items: center; padding: 15px; border: 1px solid #eee; 
        border-radius: 10px; cursor: pointer; transition: 0.3s; gap: 15px;
    }
    .payment-label:hover { background: #fff8f8; }
    .payment-label input[type="radio"] { width: 18px; height: 18px; accent-color: var(--primary-color); }
    .payment-label img { width: 35px; height: 35px; object-fit: contain; }
    .payment-label .method-info { flex: 1; }
    .payment-label .method-name { display: block; font-weight: 700; font-size: 15px; }
    .payment-label .method-desc { font-size: 12px; color: #888; }
    .payment-label.active { border-color: var(--primary-color); background: #fff8f8; }

    /* Order Summary Sidebar */
    .summary-item { display: flex; gap: 12px; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #eee; }
    .summary-item img { width: 65px; height: 65px; border-radius: 8px; border: 1px solid #f0f0f0; object-fit: cover; }
    .summary-info { flex: 1; }
    .summary-name { font-size: 13px; font-weight: 600; line-height: 1.4; color: var(--text-dark); margin-bottom: 5px; }
    .summary-price { color: var(--primary-color); font-weight: 700; font-size: 14px; }

    .price-details { margin: 20px 0; }
    .price-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: #666; }
    .total-row { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #f4f4f4; }
    .total-label { font-weight: 700; font-size: 16px; color: var(--text-dark); }
    .total-amount { font-weight: 800; font-size: 22px; color: var(--primary-color); }

    .btn-confirm { 
        width: 100%; background: var(--primary-color); color: #fff; border: none; padding: 18px; 
        border-radius: 10px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s;
        text-transform: uppercase; box-shadow: 0 4px 15px rgba(215, 0, 24, 0.2);
    }
    .btn-confirm:hover { background: #b50014; transform: translateY(-2px); }

    @media (max-width: 992px) { .checkout-grid { grid-template-columns: 1fr; } .checkout-right { position: static; } }
</style>
@endsection

@section('content')
<div class="checkout-wrapper">
    <div class="checkout-container">
        <form action="{{ route('order.store') }}" method="POST" id="orderForm">
            @csrf
            {{-- Tổng tiền ẩn để gửi lên controller --}}
            <input type="hidden" name="tong_tien_hidden" value="{{ $totalPrice }}">

            <div class="checkout-grid">
                <div class="checkout-left">
                    <div class="checkout-card">
                        <div class="card-header">
                            <i class="ri-map-pin-user-line"></i>
                            <h2>THÔNG TIN NHẬN HÀNG</h2>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Họ và tên người nhận</label>
                                <input type="text" name="ho_ten" class="form-input" value="{{ Auth::user()->ho_ten }}" placeholder="Nhập tên người nhận" required>
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <input type="tel" name="so_dien_thoai" class="form-input" value="{{ Auth::user()->so_dien_thoai }}" placeholder="Ví dụ: 0912xxxxxx" required>
                            </div>
                        </div>
                             <div class="form-group">
                                <label>Email nhận thông báo đơn hàng</label>
                                <input type="email" name="email" class="form-input" value="{{ Auth::user()->email }}" required placeholder="example@gmail.com">
                                <small style="color: #888; font-size: 12px;">Chúng tôi sẽ gửi thông tin đơn hàng và hóa đơn qua email này.</small>
                            </div>
                             <div class="form-group">
                            <label>Địa chỉ giao hàng</label>
                            <input type="text" name="dia_chi" class="form-input" placeholder="Số nhà, tên đường, phường/xã, quận/huyện..." required>
                        </div>
                        <div class="form-group">
                            <label>Ghi chú đơn hàng</label>
                            <textarea name="ghi_chu" class="form-input" rows="3" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi đến..."></textarea>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <div class="card-header">
                            <i class="ri-secure-payment-line"></i>
                            <h2>PHƯƠNG THỨC THANH TOÁN</h2>
                        </div>
                        <div class="payment-options">
                            <label class="payment-label active">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <i class="ri-truck-fill" style="font-size: 28px; color: #555;"></i>
                                <div class="method-info">
                                    <span class="method-name">Thanh toán khi nhận hàng (COD)</span>
                                    <span class="method-desc">Bạn chỉ trả tiền khi đã nhận được hàng.</span>
                                </div>
                            </label>

                            <label class="payment-label">
                                <input type="radio" name="payment_method" value="vnpay">
                                <img src="https://sandbox.vnpayment.vn/paymentv2/Images/brands/logo-vnpay.png" alt="vnpay">
                                <div class="method-info">
                                    <span class="method-name">Ví điện tử VNPAY</span>
                                    <span class="method-desc">Thanh toán qua ứng dụng ngân hàng bằng QR-Code.</span>
                                </div>
                            </label>

                            <label class="payment-label">
                                <input type="radio" name="payment_method" value="momo">
                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="momo">
                                <div class="method-info">
                                    <span class="method-name">Ví MoMo</span>
                                    <span class="method-desc">An toàn, nhanh chóng và tiện lợi.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="checkout-right">
                    <div class="checkout-card">
                        <div class="card-header">
                            <i class="ri-shopping-bag-3-line"></i>
                            <h2>ĐƠN HÀNG CỦA BẠN</h2>
                        </div>
                        
                        <div class="order-summary-list">
                            @foreach($items as $item)
                            <div class="summary-item">
                                <img src="{{ asset($item->sanPham->anh_dai_dien) }}" alt="product">
                                <div class="summary-info">
                                    <div class="summary-name">{{ $item->sanPham->ten_san_pham }}</div>
                                    <div class="summary-price">
                                        {{ number_format($item->sanPham->gia_khuyen_mai ?: $item->sanPham->gia_ban, 0, ',', '.') }}₫ 
                                        <span style="color: #999; font-weight: 400;">x{{ $item->so_luong }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="price-details">
                            <div class="price-row">
                                <span>Tạm tính ({{ count($items) }} món)</span>
                                <span>{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                            </div>
                            <div class="price-row">
                                <span>Phí vận chuyển</span>
                                <span style="color: #28a745; font-weight: 600;">Miễn phí</span>
                            </div>
                            <div class="total-row">
                                <span class="total-label">TỔNG CỘNG</span>
                                <span class="total-amount">{{ number_format($totalPrice, 0, ',', '.') }}₫</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-confirm" id="btnSubmitOrder">ĐẶT HÀNG NGAY</button>
                        
                        <p style="font-size: 12px; color: #888; text-align: center; margin-top: 15px; line-height: 1.5;">
                            Nhấn "Đặt hàng ngay" đồng nghĩa với việc bạn đồng ý với các 
                            <a href="#" style="color: #007bff; text-decoration: none;">điều khoản dịch vụ</a> của LaptopTF.
                        </p>
                    </div>
                    
                    <div style="margin-top: 25px; text-align: center;">
    <a href="{{ $backUrl }}" style="display: inline-flex; align-items: center; gap: 8px; color: #666; text-decoration: none; font-size: 15px; font-weight: 600; transition: 0.3s;" onmouseover="this.style.color='#d70018'" onmouseout="this.style.color='#666'">
        <i class="ri-arrow-left-line" style="font-size: 18px;"></i> 
        <span>Quay lại sản phẩm</span>
    </a>
</div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hiệu ứng chọn phương thức thanh toán
        const labels = document.querySelectorAll('.payment-label');
        labels.forEach(label => {
            label.addEventListener('click', () => {
                labels.forEach(l => l.classList.remove('active'));
                label.classList.add('active');
            });
        });

        // Chống nhấn nút đặt hàng nhiều lần
        const orderForm = document.getElementById('orderForm');
        const btnSubmit = document.getElementById('btnSubmitOrder');
        
        orderForm.onsubmit = function() {
            btnSubmit.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> ĐANG XỬ LÝ...';
            btnSubmit.disabled = true;
            btnSubmit.style.opacity = '0.7';
        };
    });
</script>
@endsection