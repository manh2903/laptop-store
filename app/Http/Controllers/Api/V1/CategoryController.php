<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\DanhMuc;
use App\Models\SanPham;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends BaseApiController
{
    /**
     * Lấy danh sách danh mục
     * GET /api/v1/categories?loai=menu
     */
    public function index(Request $request): JsonResponse
    {
        $query = DanhMuc::where('trang_thai', 1);

        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        if ($request->boolean('tree_only', true)) {
            // Chỉ lấy danh mục gốc kèm danh mục con
            $categories = $query->where('parent_id', 0)
                ->with(['childrenActive'])
                ->orderBy('thu_tu', 'asc')
                ->get();
        } else {
            $categories = $query->orderBy('thu_tu', 'asc')->get();
        }

        return $this->sendResponse(
            CategoryResource::collection($categories),
            'Lấy danh sách danh mục thành công'
        );
    }

    /**
     * Chi tiết danh mục kèm sản phẩm thuộc danh mục
     * GET /api/v1/categories/{slug}
     */
    public function show(string $slug, Request $request): JsonResponse
    {
        $category = DanhMuc::where('slug', $slug)
            ->where('trang_thai', 1)
            ->with(['childrenActive'])
            ->first();

        if (!$category) {
            return $this->sendError('Không tìm thấy danh mục', [], 404);
        }

        // Lấy toàn bộ ID danh mục con (nếu có) để query sản phẩm
        $categoryIds = [$category->id];
        if ($category->childrenActive && $category->childrenActive->isNotEmpty()) {
            $categoryIds = array_merge($categoryIds, $category->childrenActive->pluck('id')->toArray());
        }

        // Query sản phẩm trong danh mục
        $productQuery = SanPham::with(['danhMuc', 'thuongHieu'])
            ->whereIn('id_danh_muc', $categoryIds)
            ->where('trang_thai', 1);

        if ($request->filled('brand_id')) {
            $productQuery->where('id_thuong_hieu', $request->brand_id);
        }

        $perPage = max(1, min((int) $request->get('per_page', 12), 100));
        $products = $productQuery->orderBy('ngay_cap_nhat', 'desc')->paginate($perPage);

        return $this->sendResponse([
            'category' => new CategoryResource($category),
            'products' => [
                'items'      => ProductResource::collection($products->items()),
                'pagination' => [
                    'total'        => $products->total(),
                    'count'        => $products->count(),
                    'per_page'     => $products->perPage(),
                    'current_page' => $products->currentPage(),
                    'total_pages'  => $products->lastPage(),
                    'has_more'     => $products->hasMorePages(),
                ]
            ]
        ], 'Lấy chi tiết danh mục thành công');
    }
}
