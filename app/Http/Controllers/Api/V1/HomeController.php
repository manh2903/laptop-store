<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\BrandResource;
use Illuminate\Http\JsonResponse;

class HomeController extends BaseApiController
{
    /**
     * Lấy toàn bộ dữ liệu tổng hợp cho trang chủ
     * GET /api/v1/home
     */
    public function index(): JsonResponse
    {
        // 1. Menu chính đa cấp
        $mainMenu = DanhMuc::where('loai', 'menu')
            ->where('parent_id', 0)
            ->where('trang_thai', 1)
            ->with(['childrenActive'])
            ->orderBy('thu_tu', 'asc')
            ->get();

        // 2. Nhóm nhu cầu người dùng (Gaming, Văn phòng...)
        $needSection = DanhMuc::where('loai', 'need')
            ->where('trang_thai', 1)
            ->orderBy('thu_tu', 'asc')
            ->get();

        // 3. Thương hiệu nổi bật
        $brands = ThuongHieu::where('trang_thai', 1)
            ->orderBy('thu_tu_sap_xep', 'asc')
            ->get();

        // 4. Sản phẩm Flash Sale
        $flashSaleProducts = SanPham::where('trang_thai', 1)
            ->where('is_flash_sale', 1)
            ->with(['danhMuc', 'thuongHieu'])
            ->take(10)
            ->get();

        // 5. Sản phẩm mới nhất
        $newProducts = SanPham::where('trang_thai', 1)
            ->with(['danhMuc', 'thuongHieu'])
            ->orderBy('ngay_cap_nhat', 'desc')
            ->take(12)
            ->get();

        return $this->sendResponse([
            'main_menu'           => CategoryResource::collection($mainMenu),
            'need_section'        => CategoryResource::collection($needSection),
            'brands'              => BrandResource::collection($brands),
            'flash_sale_products' => ProductResource::collection($flashSaleProducts),
            'new_products'        => ProductResource::collection($newProducts),
        ], 'Lấy dữ liệu trang chủ thành công');
    }
}
