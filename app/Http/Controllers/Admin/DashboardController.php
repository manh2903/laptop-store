<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
// use App\Models\DonHang; // (Bỏ comment khi bạn có Model Đơn hàng)
// use App\Models\User;    // (Bỏ comment khi bạn có Model User)

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Lấy số liệu thống kê thực tế
        $totalProducts = SanPham::count();
        $totalCategories = DanhMuc::count();
        
        // Giả lập số liệu (Vì mình chưa biết bạn có bảng User/DonHang chưa)
        // Khi nào có bảng thật, bạn thay bằng: DonHang::count()
        $totalOrders = 150; 
        $totalRevenue = 50000000; // 50 triệu
        $totalCustomers = 89;

        // 2. Lấy 5 sản phẩm mới nhất để hiển thị
        $newestProducts = SanPham::latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalProducts', 
            'totalCategories', 
            'totalOrders', 
            'totalRevenue', 
            'totalCustomers',
            'newestProducts'
        ));
    }
}