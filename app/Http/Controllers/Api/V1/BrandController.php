<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ThuongHieu;
use App\Http\Resources\V1\BrandResource;
use Illuminate\Http\JsonResponse;

class BrandController extends BaseApiController
{
    /**
     * Lấy danh sách thương hiệu
     * GET /api/v1/brands
     */
    public function index(): JsonResponse
    {
        $brands = ThuongHieu::where('trang_thai', 1)
            ->orderBy('thu_tu_sap_xep', 'asc')
            ->get();

        return $this->sendResponse(
            BrandResource::collection($brands),
            'Lấy danh sách thương hiệu thành công'
        );
    }
}
