<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Models\SanPham; // Nhớ use Model SanPham
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
   // app/Http/Controllers/CartController.php

public function addToCart(Request $request)
{
    // Kiểm tra đăng nhập
    if (!Auth::check()) {
        return response()->json(['status' => 'error', 'message' => 'Vui lòng đăng nhập!'], 401);
    }

    try {
        // QUAN TRỌNG: Kiểm tra xem JS gửi 'product_id' hay 'item_id'
        $productId = $request->product_id; 
        $userId = Auth::id();

        // Kiểm tra sản phẩm tồn tại
        $product = \App\Models\SanPham::find($productId);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm không tồn tại!'], 404);
        }

        $gioHang = \App\Models\GioHang::firstOrCreate(['id_nguoi_dung' => $userId]);

        $chiTiet = \App\Models\ChiTietGioHang::firstOrNew([
            'id_gio_hang' => $gioHang->id,
            'id_san_pham' => $productId
        ]);

        $chiTiet->so_luong = ($chiTiet->exists) ? $chiTiet->so_luong + 1 : 1;
        $chiTiet->save();

        // Tính tổng số lượng để cập nhật Badge
        $totalCount = \App\Models\ChiTietGioHang::where('id_gio_hang', $gioHang->id)->sum('so_luong');

        return response()->json([
            'status' => 'success',
            'total_count' => (int)$totalCount,
            'message' => 'Đã thêm vào giỏ hàng thành công!'
        ]);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

    // App\Http\Controllers\CartController.php

public function index()
{
    $userId = Auth::id();
    
    // 1. Lấy giỏ hàng của người dùng
    $gioHang = GioHang::where('id_nguoi_dung', $userId)->first();
    
    $items = [];
    $totalPrice = 0;

    if ($gioHang) {
        // 2. Lấy chi tiết giỏ hàng kèm thông tin sản phẩm
        $items = ChiTietGioHang::where('id_gio_hang', $gioHang->id)
                                ->with('sanPham')
                                ->get();

        // 3. Tính tổng tiền cho toàn bộ giỏ hàng
        foreach ($items as $item) {
            $price = $item->sanPham->gia_khuyen_mai > 0 ? $item->sanPham->gia_khuyen_mai : $item->sanPham->gia_ban;
            $totalPrice += $price * $item->so_luong;
        }
    }

    // 4. Trả về view 'client.cart' (Khớp với file cart.blade.php trong thư mục client)
    return view('client.cart', compact('items', 'totalPrice'));
}

   // 1. Hàm cập nhật số lượng
public function updateQuantity(Request $request) {
    $item = ChiTietGioHang::find($request->item_id);
    if ($item) {
        $item->so_luong = $request->quantity;
        $item->save();

        // Tính lại tổng tiền của cả giỏ hàng để trả về cho JS
        $totalPrice = 0;
        $allItem = ChiTietGioHang::where('id_gio_hang', $item->id_gio_hang)->get();
        foreach($allItem as $i) {
            $price = $i->sanPham->gia_khuyen_mai ?: $i->sanPham->gia_ban;
            $totalPrice += $price * $i->so_luong;
        }

        return response()->json([
            'status' => 'success',
            'new_item_total' => number_format(($item->sanPham->gia_khuyen_mai ?: $item->sanPham->gia_ban) * $item->so_luong, 0, ',', '.') . '₫',
            'new_cart_total' => number_format($totalPrice, 0, ',', '.') . '₫'
        ]);
    }
}

// 2. Hàm xóa sản phẩm khỏi giỏ
    public function removeItem(Request $request) {
        // Lấy item_id từ body (Axios gửi lên)
        $item = \App\Models\ChiTietGioHang::find($request->item_id);
        
        if ($item) {
            $item->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 404);
    }

    public function checkout()
{
    $userId = Auth::id();
    $gioHang = \App\Models\GioHang::where('id_nguoi_dung', $userId)->first();
    
    if (!$gioHang || $gioHang->chiTietGioHang->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
    }

    $items = $gioHang->chiTietGioHang()->with('sanPham')->get();
    $totalPrice = 0;
    foreach ($items as $item) {
        $price = $item->sanPham->gia_khuyen_mai ?: $item->sanPham->gia_ban;
        $totalPrice += $price * $item->so_luong;
    }

    return view('client.checkout', compact('items', 'totalPrice'));
}

public function processOrder(Request $request)
{
    // Tại đây bạn sẽ viết logic lưu vào bảng Orders (Đơn hàng) và OrderDetails
    // Sau đó xóa sạch giỏ hàng.
    // Tạm thời trả về thông báo thành công:
    return "Cảm ơn bạn đã đặt hàng! Đơn hàng đang được xử lý.";
}

}