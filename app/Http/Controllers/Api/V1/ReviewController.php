<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\DanhGia;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ReviewController extends BaseApiController
{
    /**
     * Gửi đánh giá sản phẩm
     * POST /api/v1/reviews
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:san_pham,id',
            'rating'     => 'required|integer|min:1|max:5',
            'content'    => 'required|string|min:3',
            'hinh_anh'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'product_id.required' => 'Mã sản phẩm không được để trống.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'rating.required'     => 'Vui lòng chọn số sao đánh giá (1-5).',
            'rating.min'          => 'Số sao tối thiểu là 1.',
            'rating.max'          => 'Số sao tối đa là 5.',
            'content.required'    => 'Vui lòng nhập nội dung đánh giá.',
            'content.min'         => 'Nội dung đánh giá phải có ít nhất 3 ký tự.',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Dữ liệu đánh giá không hợp lệ', $validator->errors(), 422);
        }

        $imagePath = null;
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/reviews'), $filename);
            $imagePath = 'uploads/reviews/' . $filename;
        }

        $review = DanhGia::create([
            'id_nguoi_dung' => Auth::id() ?? 1, // Fallback nếu guest
            'id_san_pham'   => $request->product_id,
            'so_sao'        => $request->rating,
            'noi_dung'      => $request->input('content'),
            'hinh_anh'      => $imagePath,
            'thoi_gian'     => now(),
            'trang_thai'    => 1,
        ]);

        return $this->sendResponse([
            'id'         => $review->id,
            'product_id' => $review->id_san_pham,
            'rating'     => $review->so_sao,
            'content'    => $review->noi_dung,
        ], 'Gửi đánh giá sản phẩm thành công!', 201);
    }

    /**
     * Xóa đánh giá
     * DELETE /api/v1/reviews/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        if (!Auth::check()) {
            return $this->sendError('Chưa xác thực quyền truy cập.', [], 401);
        }

        $review = DanhGia::find($id);
        if (!$review) {
            return $this->sendError('Không tìm thấy đánh giá cần xóa.', [], 404);
        }

        if ($review->id_nguoi_dung != Auth::id() && Auth::user()->id_vai_tro != 1) {
            return $this->sendError('Bạn không có quyền xóa đánh giá này.', [], 403);
        }

        if ($review->hinh_anh && File::exists(public_path($review->hinh_anh))) {
            File::delete(public_path($review->hinh_anh));
        }

        $review->delete();

        return $this->sendResponse(['id' => $id], 'Đã xóa đánh giá thành công.');
    }
}
