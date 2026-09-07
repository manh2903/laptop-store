@extends('admin.layouts.main')
@section('title', 'Chi tiết hóa đơn')
@section('content')
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
        <div>
            <h1 class="text-2xl font-bold">Chi tiết đơn hàng #DH001248</h1>
            <p class="text-sm text-gray-500 mt-1">
                Ngày tạo: 24/11/2025 14:30
            </p>
        </div>
        <a href="order_list.html" class="btn btn-warning">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form action="update_order_status.php" method="POST">
        <input type="hidden" name="order_id" value="DH001248" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold">Thông tin chung</h2>
                    <span class="status-big shipping">Đang giao hàng</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h4 class="font-bold text-lg mb-3 text-gray-700">
                            Khách hàng
                        </h4>
                        <p class="mb-1"><strong>Họ tên:</strong> Nguyễn Văn Hùng</p>
                        <p class="mb-1"><strong>SĐT:</strong> 0901234567</p>
                        <p class="mb-1"><strong>Email:</strong> hung@gmail.com</p>
                        <p class="mb-1">
                            <strong>Địa chỉ:</strong> 123 Đường Láng, Đống Đa, Hà Nội
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-3 text-gray-700">
                            Vận chuyển & Thanh toán
                        </h4>
                        <p class="mb-1"><strong>Phí ship:</strong> 50.000 ₫</p>
                        <p class="mb-1"><strong>Đơn vị:</strong> Giao Hàng Nhanh</p>
                        <p class="mb-1">
                            <strong>Thanh toán:</strong> Chuyển khoản ngân hàng
                        </p>
                        <p class="mb-1">
                            <strong>Ghi chú:</strong> Giao giờ hành chính
                        </p>
                    </div>
                </div>

                <h4 class="font-bold text-lg mb-4 text-gray-700">
                    Danh sách sản phẩm
                </h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="font-bold">Asus ROG Strix G15 2024</div>
                                <div class="text-sm text-gray-500">
                                    RTX 4070, 16GB RAM
                                </div>
                            </td>
                            <td class="text-center">1</td>
                            <td class="text-right">32.990.000 ₫</td>
                            <td class="text-right font-bold text-blue-600">
                                32.990.000 ₫
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right font-bold text-gray-600">
                                Tổng tiền hàng:
                            </td>
                            <td class="text-right font-bold">32.990.000 ₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right text-gray-600">
                                Phí vận chuyển:
                            </td>
                            <td class="text-right">50.000 ₫</td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td colspan="3" class="text-right text-xl font-bold">
                                TỔNG CỘNG:
                            </td>
                            <td class="text-right text-2xl font-bold text-blue-600">
                                33.040.000 ₫
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="lg:col-span-1 lg:border-l lg:pl-8 border-t lg:border-t-0 pt-6 lg:pt-0 border-gray-200">
                <h3 class="font-bold text-lg mb-4">Cập nhật trạng thái</h3>

                <div class="space-y-3">
                    <button type="submit" name="status" value="confirmed" class="btn btn-block btn-primary">
                        <i class="fas fa-check mr-2"></i> Xác nhận đơn
                    </button>
                    <button type="submit" name="status" value="processing" class="btn btn-block btn-info">
                        <i class="fas fa-spinner mr-2"></i> Đang xử lý
                    </button>
                    <button type="submit" name="status" value="shipping" class="btn btn-block btn-warning">
                        <i class="fas fa-truck mr-2"></i> Đang giao hàng
                    </button>
                    <button type="submit" name="status" value="delivered" class="btn btn-block btn-success">
                        <i class="fas fa-box-open mr-2"></i> Giao thành công
                    </button>
                    <button type="submit" name="status" value="cancelled" class="btn btn-block btn-danger"
                        onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?')">
                        <i class="fas fa-times mr-2"></i> Hủy đơn hàng
                    </button>
                </div>

                <hr class="my-6 border-gray-200" />

                <h3 class="font-bold text-lg mb-4">Lịch sử đơn hàng</h3>
                <div class="relative pl-4 border-l-2 border-gray-200 space-y-4">
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 bg-gray-300 rounded-full"></div>
                        <p class="text-sm text-gray-500">24/11 14:30</p>
                        <p class="text-sm font-bold">Đơn hàng được tạo</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 bg-blue-500 rounded-full"></div>
                        <p class="text-sm text-gray-500">24/11 14:45</p>
                        <p class="text-sm font-bold">Đã xác nhận</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 bg-yellow-400 rounded-full ring-4 ring-yellow-100">
                        </div>
                        <p class="text-sm text-blue-600 font-bold">25/11 13:30</p>
                        <p class="text-sm font-bold text-yellow-600">
                            Đang giao hàng
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
