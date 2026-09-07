<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;

class HomeController extends Controller
{
    
    /**
     * Dữ liệu dùng chung cho Header/Menu
     */
    private function getSharedData() {
        return [
            // Menu đa cấp: Laptop, Linh kiện...
            'mainMenu' => DanhMuc::where('loai', 'menu')
                                 ->where('parent_id', 0)
                                 ->where('trang_thai', 1)
                                 ->with(['childrenActive'])
                                 ->orderBy('thu_tu', 'asc')
                                 ->get(),

            // Danh sách nhu cầu: Gaming, Văn phòng...
            'needSection' => DanhMuc::where('loai', 'need')
                                    ->where('trang_thai', 1)
                                    ->orderBy('thu_tu', 'asc')
                                    ->get(),
        ];
    }

    // 1. TRANG CHỦ
    public function index()
    {
        $data = $this->getSharedData();

        $brands = ThuongHieu::where('trang_thai', 1)
                            ->orderBy('thu_tu_sap_xep', 'asc')
                            ->get();

        $flashSaleProducts = SanPham::where('trang_thai', 1)
                                    ->where('is_flash_sale', 1)
                                    ->take(10)
                                    ->get();

        $allProducts = SanPham::where('trang_thai', 1)
                              ->orderBy('ngay_cap_nhat', 'desc') // Sắp xếp mới nhất
                              ->paginate(12);

        return view('client.home', array_merge($data, [
            'brands'            => $brands,
            'flashSaleProducts' => $flashSaleProducts,
            'allProducts'       => $allProducts,
            'isHome'            => true
        ]));
    }
}