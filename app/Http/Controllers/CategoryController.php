<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DanhMuc;

class CategoryController extends Controller
{
    private function getSharedData() {
        return [
            'mainMenu' => DanhMuc::where('loai', 'menu')->where('parent_id', 0)->where('trang_thai', 1)->with('childrenActive')->orderBy('thu_tu', 'asc')->get(),
            'needSection' => DanhMuc::where('loai', 'need')->where('trang_thai', 1)->orderBy('thu_tu', 'asc')->get(),
        ];
    }

    public function show($id) // Đổi slug thành id
    {
        $data = $this->getSharedData();

        // Tìm danh mục
        $category = DanhMuc::where('trang_thai', 1)->findOrFail($id);

        // Lấy danh sách ID danh mục con (đệ quy đơn giản 1 cấp)
        $categoryIds = [$category->id];
        $childIds = DanhMuc::where('parent_id', $category->id)->pluck('id')->toArray();
        $categoryIds = array_merge($categoryIds, $childIds);

        // Lọc sản phẩm
        $products = SanPham::whereIn('id_danh_muc', $categoryIds)
            ->where('trang_thai', 1)
            ->orderBy('ngay_cap_nhat', 'desc')
            ->paginate(12);

        return view('client.product_category', array_merge($data, [ // Kiểm tra tên view
            'category' => $category,
            'products' => $products
        ]));
    }
}