<?php

namespace App\Http\Controllers;

use App\Models\ThuongHieu;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Lấy danh sách tất cả thương hiệu đang hoạt động
     */
    public function index()
    {
        $brands = ThuongHieu::where('trang_thai', 1)
                            ->orderBy('thu_tu_sap_xep', 'asc')
                            ->get();
        
        return view('client.brands', compact('brands'));
    }

    /**
     * Hiển thị sản phẩm theo từng thương hiệu cụ thể (ví dụ: /thuong-hieu/asus)
     */
    public function show($slug)
    {
        $brand = ThuongHieu::where('slug', $slug)
                           ->where('trang_thai', 1)
                           ->firstOrFail();

        // Logic lấy sản phẩm thuộc hãng này sẽ được thêm ở đây sau
        return view('client.brand_detail', compact('brand'));
    }
    
}