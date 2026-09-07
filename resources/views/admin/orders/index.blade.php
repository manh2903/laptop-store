@extends('admin.layouts.main')
@section('title', 'Danh sách hóa đơn')
@section('content')
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold">Danh sách đơn hàng</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <select class="px-4 py-2 border rounded-lg">
                <option>Tất cả trạng thái</option>
                <option>Chờ xác nhận</option>
                <option>Đã xác nhận</option>
                <option>Đang xử lý</option>
                <option>Đang giao</option>
                <option>Đã giao</option>
                <option>Đã hủy</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số ĐT</th>
                    <th>Sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold">#DH001248</td>
                    <td>Nguyễn Văn Hùng</td>
                    <td>0901234567</td>
                    <td>Asus ROG Strix × 1</td>
                    <td class="text-xl font-bold text-blue-600">32.990.000 ₫</td>
                    <td>24/11/2025 14:30</td>
                    <td><span class="status pending">Chờ xác nhận</span></td>
                    <td class="text-center">
                        <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-info">Xem chi tiết</a>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold">#DH001247</td>
                    <td>Trần Thị Lan</td>
                    <td>0912345678</td>
                    <td>Dell XPS 13 × 1</td>
                    <td class="text-xl font-bold text-blue-600">44.180.000 ₫</td>
                    <td>24/11/2025 11:15</td>
                    <td><span class="status shipping">Đang giao</span></td>
                    <td class="text-center">
                        <a href="admin/orders/order_detail" class="btn btn-sm btn-info">Xem chi tiết</a>
                    </td>
                </tr>
                <tr>
                    <td class="font-bold">#DH001245</td>
                    <td>Lê Minh Tuấn</td>
                    <td>0934567890</td>
                    <td>MSI Stealth 16 × 1</td>
                    <td class="text-xl font-bold text-blue-600">68.990.000 ₫</td>
                    <td>23/11/2025 20:45</td>
                    <td><span class="status delivered">Đã giao</span></td>
                    <td class="text-center">
                        <a href="admin/orders/order_detail" class="btn btn-sm btn-info">Xem chi tiết</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-8">
        <p>Hiển thị 1-10 của 1.847 đơn</p>
        <div class="flex gap-2">
            <button class="btn btn-sm">Trước</button>
            <button class="btn btn-sm btn-primary">1</button>
            <button class="btn btn-sm">2</button>
            <button class="btn btn-sm">3</button>
            <button class="btn btn-sm">Sau</button>
        </div>
    </div>
@endsection
