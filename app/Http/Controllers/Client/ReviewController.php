<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhGia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 

class ReviewController extends Controller
{
    public function store(Request $request)
{
    // 1. Validate dữ liệu
    $request->validate([
        'product_id' => 'required|exists:san_pham,id',
        'rating'     => 'required|integer|min:1|max:5',
        'content'    => 'required|string|min:3', // Tên trong validate giữ nguyên theo name input
        'hinh_anh'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
    ], [
        'content.required' => 'Vui lòng nhập nội dung đánh giá',
        'rating.required'  => 'Vui lòng chọn số sao',
        'hinh_anh.image'   => 'File tải lên phải là hình ảnh',
        'hinh_anh.max'     => 'Dung lượng ảnh không được quá 2MB'
    ]);

    // 2. Chuẩn bị dữ liệu lưu DB
    $data = [
        'id_nguoi_dung' => Auth::id(),
        'id_san_pham'   => $request->product_id,
        'so_sao'        => $request->rating,
        
        // [SỬA LỖI Ở ĐÂY]: Dùng input('content') thay vì ->content
        'noi_dung'      => $request->input('content'), 
        
        'thoi_gian'     => now(),
        'trang_thai'    => 1 
    ];

    // 3. Xử lý Upload Ảnh (Nếu có)
    if ($request->hasFile('hinh_anh')) {
        $file = $request->file('hinh_anh');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/reviews'), $filename);
        $data['hinh_anh'] = 'uploads/reviews/' . $filename;
    }

    // 4. Lưu vào Database
    DanhGia::create($data); 

    // 5. Quay lại trang sản phẩm
    return redirect()->back()
        ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!')
        ->withFragment('reviews-section'); // Thêm cái này để nó tự cuộn xuống phần đánh giá
}

    // 2. XÓA ĐÁNH GIÁ (DESTROY)
    public function destroy($id)
    {
        $review = DanhGia::find($id);

        // Chỉ cho phép xóa nếu là chủ nhân
        if ($review && $review->id_nguoi_dung == Auth::id()) {
            // Xóa ảnh cũ nếu có
            if ($review->hinh_anh && File::exists(public_path($review->hinh_anh))) {
                File::delete(public_path($review->hinh_anh));
            }
            $review->delete();

            return redirect()->back()->with('success', 'Đã xóa đánh giá.')->withFragment('reviews-section');
        }

        return redirect()->back()->with('error', 'Không có quyền xóa.');
    }
}