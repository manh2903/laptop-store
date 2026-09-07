<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GioHang; 
use App\Models\NguoiDung; // Đảm bảo dòng này chính xác
use Illuminate\Http\Request;

class AdminCartController extends Controller
{
    public function index()
    {
        $carts = GioHang::with(['nguoiDung', 'chiTiet.sanPham'])
                        ->has('chiTiet')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(15);

        return view('admin.carts.index', compact('carts'));
    }

   public function show($id)
{
    try {
        // Sử dụng đường dẫn tuyệt đối để tránh lỗi Class not found
        $cart = \App\Models\GioHang::with(['chiTiet.sanPham', 'nguoiDung'])->findOrFail($id);
        
        return response()->json($cart);
    } catch (\Exception $e) {
        // Nếu lỗi, nó sẽ trả về thông báo lỗi cụ thể thay vì quay vòng
        return response()->json([
            'message' => 'Lỗi PHP: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
}