@extends('layout.master')
@section('content')
<div style="text-align: center; padding: 80px 20px;">
    <i class="ri-checkbox-circle-fill" style="font-size: 70px; color: #28a745;"></i>
    <h2 style="margin: 20px 0;">ĐẶT HÀNG THÀNH CÔNG!</h2>
    <p>Cảm ơn bạn đã tin tưởng LaptopTF. Mã đơn hàng của bạn là: <strong>{{ $donHang->ma_don_hang }}</strong></p>
    <div style="margin-top: 30px;">
        <a href="{{ route('home') }}" style="background: #d70018; color: #fff; padding: 12px 25px; border-radius: 5px; text-decoration: none;">TIẾP TỤC MUA SẮM</a>
    </div>
</div>
@endsection