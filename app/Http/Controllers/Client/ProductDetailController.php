<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\DanhGia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Thêm cái này để dùng selectRaw nếu cần

class ProductDetailController extends Controller
{
    // --- HÀM LẤY DỮ LIỆU CHUNG CHO HEADER/MENU ---
    private function getSharedData() {
        return [
            'mainMenu' => DanhMuc::where('loai', 'menu')
                ->where('parent_id', 0)
                ->where('trang_thai', 1)
                ->with('childrenActive')
                ->orderBy('thu_tu', 'asc')
                ->get(),
            'needSection' => DanhMuc::where('loai', 'need')
                ->where('trang_thai', 1)
                ->orderBy('thu_tu', 'asc')
                ->get(),
        ];
    }

    // --- TRANG CHI TIẾT SẢN PHẨM ---
    public function show($slug) 
    {
        $data = $this->getSharedData();

        // 1. Lấy thông tin sản phẩm
        $product = SanPham::with(['danhMuc', 'thuongHieu']) 
            ->where('trang_thai', 1)
            ->where('slug', $slug) 
            ->firstOrFail(); 

        // Tăng lượt xem
        $product->increment('luot_xem');

        // =================================================================
        // LOGIC 2: SẢN PHẨM TƯƠNG TỰ (Cùng Hãng + Giá ±10%)
        // =================================================================
        $currentPrice = $product->gia_khuyen_mai > 0 ? $product->gia_khuyen_mai : $product->gia_ban;
        $minPrice = $currentPrice * 0.9;
        $maxPrice = $currentPrice * 1.1;

        $relatedProducts = SanPham::where('trang_thai', 1)
            ->where('id_thuong_hieu', $product->id_thuong_hieu)
            ->where('id', '!=', $product->id)
            ->where(function($query) use ($minPrice, $maxPrice) {
                $query->whereBetween('gia_khuyen_mai', [$minPrice, $maxPrice])
                      ->orWhere(function($q) use ($minPrice, $maxPrice) {
                          $q->where('gia_khuyen_mai', 0)
                            ->whereBetween('gia_ban', [$minPrice, $maxPrice]);
                      });
            })
            ->orderBy('gia_ban', 'asc')
            ->take(15)
            ->get();

        // =================================================================
        // LOGIC 3: HÀNG CŨ (Cùng Hãng + Cập nhật > 5 tháng trước)
        // =================================================================
        $fiveMonthsAgo = Carbon::now()->subMonths(5);
        $excludeIds = $relatedProducts->pluck('id')->push($product->id);

        $usedProducts = SanPham::where('trang_thai', 1)
            ->where('id_thuong_hieu', $product->id_thuong_hieu)
            ->where('id', '!=', $product->id) // Sửa lại logic exclude cho chắc chắn
            ->whereNotIn('id', $excludeIds)
            ->where('ngay_cap_nhat', '<', $fiveMonthsAgo) 
            ->orderBy('ngay_cap_nhat', 'asc')
            ->take(15)
            ->get();

        // =================================================================
        // LOGIC 4: LẤY ĐÁNH GIÁ & THỐNG KÊ (VIP PRO)
        // =================================================================
        
        // Lưu ý: Dùng get() thay vì paginate() để JS ở View có thể lọc và xử lý "Xem thêm"
        // trên toàn bộ danh sách mà không cần load lại trang.
        $reviews = DanhGia::with('user')
            ->where('id_san_pham', $product->id)
            ->where('trang_thai', 1)
            ->orderBy('created_at', 'desc')
            ->get(); 

        // Tính điểm trung bình và tổng số
        $avgRating = $reviews->avg('so_sao') ?? 0;
        $totalReviews = $reviews->count();

        // Thống kê số lượng từng sao (Để vẽ biểu đồ Progress Bar)
        // Cách này tận dụng Collection của Laravel, không cần query lại DB
        $starCounts = [
            5 => $reviews->where('so_sao', 5)->count(),
            4 => $reviews->where('so_sao', 4)->count(),
            3 => $reviews->where('so_sao', 3)->count(),
            2 => $reviews->where('so_sao', 2)->count(),
            1 => $reviews->where('so_sao', 1)->count(),
        ];

       // CHUYỂN DỮ LIỆU SANG VIEW (Bao gồm cả tên Tiếng Anh và Tiếng Việt để tránh lỗi)
        return view('client.product_detail', array_merge($data, [
            // 1. Cung cấp cả 2 tên cho Sản phẩm
            'product' => $product,      // Sửa lỗi dòng 13 (Undefined variable $product)
            'sanPham' => $product,      // Sửa lỗi dòng 189 (Undefined variable $sanPham)

            // 2. Cung cấp cả 2 tên cho Điểm trung bình
            'avgRating' => round($avgRating, 1),      // Sửa lỗi dòng 825 (Undefined variable $avgRating)
            'diemTrungBinh' => round($avgRating, 1),  // Sửa lỗi dòng 204 (Undefined variable $diemTrungBinh)

            // 3. Cung cấp cả 2 tên cho Tổng đánh giá
            'totalReviews' => $totalReviews,          // Phòng hờ các đoạn code cũ dùng tên này
            'tongDanhGia' => $totalReviews,           // Sửa lỗi dòng 206 (Undefined variable $tongDanhGia)

            // 4. Các biến khác giữ nguyên
            'relatedProducts' => $relatedProducts,
            'usedProducts' => $usedProducts,
            'reviews' => $reviews,
            'starCounts' => $starCounts
        ]));
    }

    // Thêm vào trong class ProductDetailController

public function compare(Request $request)
    {
        $ids = $request->query('ids');

        if (!$ids) {
            return redirect('/')->with('error', 'Chưa chọn sản phẩm');
        }

        $idArray = explode(',', $ids);

        $products = SanPham::whereIn('id', $idArray)
                    ->where('trang_thai', 1)
                    ->with('thongSo') // Eager load
                    ->get();

        return view('client.compare', compact('products'));
    }


}