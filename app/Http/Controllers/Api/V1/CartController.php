<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseApiController
{
    /**
     * Lấy thông tin giỏ hàng
     * GET /api/v1/cart
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? $request->header('X-User-Id') ?? $request->user_id;
        if (!$userId) {
            return $this->sendError('Vui lòng đăng nhập để xem giỏ hàng!', [], 401);
        }

        $gioHang = GioHang::where('id_nguoi_dung', $userId)->first();

        if (!$gioHang) {
            return $this->sendResponse([
                'items'       => [],
                'total_items' => 0,
                'total_price' => 0,
            ], 'Giỏ hàng đang trống.');
        }

        $details = ChiTietGioHang::where('id_gio_hang', $gioHang->id)
            ->with(['sanPham'])
            ->get();

        if ($details->isEmpty()) {
            return $this->sendResponse([
                'items'       => [],
                'total_items' => 0,
                'total_price' => 0,
            ], 'Giỏ hàng của bạn đang trống.');
        }

        $items = [];
        $totalPrice = 0;
        $totalCount = 0;

        foreach ($details as $detail) {
            if ($detail->sanPham) {
                $price = $detail->sanPham->gia_khuyen_mai > 0 ? $detail->sanPham->gia_khuyen_mai : $detail->sanPham->gia_ban;
                $subTotal = $price * $detail->so_luong;
                $totalPrice += $subTotal;
                $totalCount += $detail->so_luong;

                $items[] = [
                    'id'           => $detail->id,
                    'product_id'   => $detail->id_san_pham,
                    'product_name' => $detail->sanPham->ten_san_pham,
                    'product_slug' => $detail->sanPham->slug,
                    'price'        => (float) $price,
                    'quantity'     => $detail->so_luong,
                    'subtotal'     => (float) $subTotal,
                    'image'        => $detail->sanPham->anh_dai_dien ? asset($detail->sanPham->anh_dai_dien) : null,
                ];
            }
        }

        return $this->sendResponse([
            'items'       => $items,
            'total_items' => $totalCount,
            'total_price' => $totalPrice,
        ], 'Lấy thông tin giỏ hàng thành công.');
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * POST /api/v1/cart/add
     */
    public function add(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? $request->header('X-User-Id') ?? $request->user_id;
        if (!$userId) {
            return $this->sendError('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!', [], 401);
        }

        $productId = $request->product_id ?? $request->id_san_pham;
        if (empty($productId)) {
            return $this->sendError('Mã sản phẩm không được để trống.', [], 422);
        }

        $product = SanPham::where('trang_thai', 1)->find($productId);
        if (!$product) {
            return $this->sendError('Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.', [], 404);
        }

        $quantity = max(1, (int) $request->get('quantity', 1));

        // Kiểm tra số lượng tồn kho
        if ($product->so_luong_ton !== null && $quantity > $product->so_luong_ton) {
            return $this->sendError("Số lượng yêu cầu vượt quá số lượng tồn kho (Còn lại: {$product->so_luong_ton}).", [], 422);
        }

        $gioHang = GioHang::firstOrCreate(['id_nguoi_dung' => $userId]);

        $chiTiet = ChiTietGioHang::firstOrNew([
            'id_gio_hang' => $gioHang->id,
            'id_san_pham' => $productId,
        ]);

        $newQuantity = ($chiTiet->exists) ? $chiTiet->so_luong + $quantity : $quantity;
        if ($product->so_luong_ton !== null && $newQuantity > $product->so_luong_ton) {
            return $this->sendError("Số lượng yêu cầu vượt quá số lượng tồn kho (Còn lại: {$product->so_luong_ton}).", [], 422);
        }

        $chiTiet->so_luong = $newQuantity;
        $chiTiet->save();

        $totalCount = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->sum('so_luong');

        return $this->sendResponse([
            'cart_id'     => $gioHang->id,
            'product_id'  => $productId,
            'quantity'    => $chiTiet->so_luong,
            'total_count' => (int) $totalCount,
        ], 'Đã thêm sản phẩm vào giỏ hàng thành công!');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     * POST /api/v1/cart/update
     */
    public function update(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? $request->header('X-User-Id') ?? $request->user_id;
        if (!$userId) {
            return $this->sendError('Vui lòng đăng nhập để cập nhật giỏ hàng!', [], 401);
        }

        $gioHang = GioHang::firstOrCreate(['id_nguoi_dung' => $userId]);

        $itemId = $request->item_id;
        $quantity = max(1, (int) $request->get('quantity', 1));

        $item = null;
        if ($itemId) {
            $item = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->find($itemId);
        }
        if (!$item && $request->filled('product_id')) {
            $item = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->where('id_san_pham', $request->product_id)->first();
        }
        if (!$item) {
            $item = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->first();
        }

        if (!$item) {
            return $this->sendError('Không tìm thấy sản phẩm trong giỏ hàng.', [], 404);
        }

        $item->so_luong = $quantity;
        $item->save();

        $details = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->with('sanPham')->get();
        $totalPrice = $details->sum(fn($it) => ($it->sanPham ? ($it->sanPham->gia_khuyen_mai > 0 ? $it->sanPham->gia_khuyen_mai : $it->sanPham->gia_ban) * $it->so_luong : 0));

        return $this->sendResponse([
            'item_id'     => $item->id,
            'quantity'    => $quantity,
            'total_price' => (float) $totalPrice,
        ], 'Cập nhật số lượng thành công!');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * POST /api/v1/cart/remove
     */
    public function remove(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? $request->header('X-User-Id') ?? $request->user_id;
        if (!$userId) {
            return $this->sendError('Vui lòng đăng nhập để xóa sản phẩm!', [], 401);
        }

        $gioHang = GioHang::where('id_nguoi_dung', $userId)->first();
        if (!$gioHang) {
            return $this->sendResponse([], 'Giỏ hàng đã trống.');
        }

        $itemId = $request->item_id;
        $item = null;
        if ($itemId) {
            $item = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->find($itemId);
        }
        if (!$item && $request->filled('product_id')) {
            $item = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->where('id_san_pham', $request->product_id)->first();
        }
        if (!$item) {
            $item = ChiTietGioHang::where('id_gio_hang', $gioHang->id)->first();
        }

        if ($item) {
            $item->delete();
        }

        return $this->sendResponse([
            'deleted_item_id' => $itemId,
        ], 'Đã xóa sản phẩm khỏi giỏ hàng thành công!');
    }
}
