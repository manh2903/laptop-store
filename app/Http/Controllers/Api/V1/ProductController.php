<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;
use App\Http\Resources\V1\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseApiController
{
    /**
     * Danh sách sản phẩm (hỗ trợ tìm kiếm, lọc và phân trang)
     * GET /api/v1/products
     */
    public function index(Request $request): JsonResponse
    {
        $query = SanPham::with(['danhMuc', 'thuongHieu'])
            ->where('trang_thai', 1);

        // 1. Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('ten_san_pham', 'like', "%{$keyword}%")
                  ->orWhere('ma_sku', 'like', "%{$keyword}%");
            });
        }

        // 2. Lọc theo Danh mục
        if ($request->filled('category_id')) {
            $query->where('id_danh_muc', $request->category_id);
        } elseif ($request->filled('category_slug')) {
            $category = DanhMuc::where('slug', $request->category_slug)->first();
            if ($category) {
                $query->where('id_danh_muc', $category->id);
            }
        }

        // 3. Lọc theo Thương hiệu
        if ($request->filled('brand_id')) {
            $query->where('id_thuong_hieu', $request->brand_id);
        } elseif ($request->filled('brand_slug')) {
            $brand = ThuongHieu::where('slug', $request->brand_slug)->first();
            if ($brand) {
                $query->where('id_thuong_hieu', $brand->id);
            }
        }

        // 4. Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('gia_ban', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('gia_ban', '<=', (float) $request->max_price);
        }

        // 5. Lọc theo RAM / Cấu hình
        if ($request->filled('ram')) {
            $ram = $request->ram;
            $query->whereHas('thongSo', function ($q) use ($ram) {
                $q->where('gia_tri', 'like', "%{$ram}%");
            });
        }

        // 6. Lọc sản phẩm Flash Sale
        if ($request->boolean('is_flash_sale')) {
            $query->where('is_flash_sale', 1);
        }

        // 6. Sắp xếp
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'views':
                $query->orderBy('luot_xem', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('ngay_cap_nhat', 'desc');
                break;
        }

        // 7. Phân trang
        $perPage = max(1, min((int) $request->get('per_page', 12), 100));
        $products = $query->paginate($perPage);

        return $this->sendResponse([
            'items' => ProductResource::collection($products->items()),
            'pagination' => [
                'total'        => $products->total(),
                'count'        => $products->count(),
                'per_page'     => $products->perPage(),
                'current_page' => $products->currentPage(),
                'total_pages'  => $products->lastPage(),
                'has_more'     => $products->hasMorePages(),
            ]
        ], 'Lấy danh sách sản phẩm thành công');
    }

    /**
     * Xem chi tiết sản phẩm theo slug hoặc ID
     * GET /api/v1/products/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        $product = SanPham::with([
            'danhMuc',
            'thuongHieu',
            'thongSo',
            'hinhAnh',
            'bienThe',
            'cauHinh'
        ])
        ->where('trang_thai', 1)
        ->where(function ($q) use ($slug) {
            $q->where('slug', $slug);
            if (is_numeric($slug)) {
                $q->orWhere('id', $slug);
            }
        })
        ->first();

        if (!$product) {
            return $this->sendError('Không tìm thấy sản phẩm hoặc sản phẩm đã ngừng kinh doanh', [], 404);
        }

        // Tăng lượt xem
        $product->increment('luot_xem');

        // Lấy sản phẩm tương tự (cùng hãng, khoảng giá tương đương)
        $currentPrice = $product->gia_khuyen_mai > 0 ? $product->gia_khuyen_mai : $product->gia_ban;
        $minPrice = $currentPrice * 0.85;
        $maxPrice = $currentPrice * 1.15;

        $relatedProducts = SanPham::where('trang_thai', 1)
            ->where('id_thuong_hieu', $product->id_thuong_hieu)
            ->where('id', '!=', $product->id)
            ->whereBetween('gia_ban', [$minPrice, $maxPrice])
            ->take(8)
            ->get();

        return $this->sendResponse([
            'product'          => new ProductResource($product),
            'related_products' => ProductResource::collection($relatedProducts),
        ], 'Lấy chi tiết sản phẩm thành công');
    }

    /**
     * So sánh danh sách sản phẩm
     * GET /api/v1/products/compare?ids=1,2
     */
    public function compare(Request $request): JsonResponse
    {
        $idsParam = $request->get('ids');
        if (!$idsParam) {
            return $this->sendError('Vui lòng cung cấp danh sách ID sản phẩm cần so sánh (vd: ?ids=1,2)', [], 422);
        }

        $ids = is_array($idsParam) ? $idsParam : explode(',', $idsParam);
        $products = SanPham::with(['thongSo', 'thuongHieu', 'danhMuc'])
            ->whereIn('id', $ids)
            ->where('trang_thai', 1)
            ->get();

        return $this->sendResponse(
            ProductResource::collection($products),
            'Lấy thông tin so sánh sản phẩm thành công'
        );
    }

    /**
     * Gợi ý tìm kiếm tự động (Autocomplete)
     * GET /api/v1/products/suggest?keyword=asus
     */
    public function suggest(Request $request): JsonResponse
    {
        $keyword = trim($request->get('keyword', ''));
        if (!$keyword) {
            return $this->sendResponse([], 'Không có từ khóa gợi ý');
        }

        $items = SanPham::where('trang_thai', 1)
            ->where('ten_san_pham', 'like', "%{$keyword}%")
            ->select('id', 'ten_san_pham', 'slug', 'gia_ban', 'gia_khuyen_mai', 'anh_dai_dien')
            ->take(5)
            ->get();

        return $this->sendResponse($items, 'Lấy danh sách gợi ý tìm kiếm thành công');
    }
}

