<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\ThuongHieu;
use App\Models\HinhAnhSanPham;
use App\Models\ThongSoKyThuat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BienTheSanPham;
use App\Models\CauHinhSanPham; // <--- Thêm dòng này

class AdminProductController extends Controller
{
    // 1. INDEX
    public function index(Request $request)
    {
        $query = SanPham::with(['danhMuc', 'thuongHieu']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_san_pham', 'like', "%{$keyword}%")
                  ->orWhere('ma_sku', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category_id')) $query->where('id_danh_muc', $request->category_id);
        if ($request->filled('brand_id')) $query->where('id_thuong_hieu', $request->brand_id);

        if ($request->filled('status')) {
            if ($request->status == 'visible') $query->where('trang_thai', 1);
            elseif ($request->status == 'hidden') $query->where('trang_thai', 0);
        }

        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'out_of_stock': $query->where('so_luong_ton', '<=', 0); break;
                case 'low_stock': $query->where('so_luong_ton', '>', 0)->where('so_luong_ton', '<=', 5); break;
                case 'in_stock': $query->where('so_luong_ton', '>', 5); break;
            }
        }

        $products = $query->orderBy('ngay_cap_nhat', 'desc')->paginate(10);
        $categories = DanhMuc::where('parent_id', '!=', 0)->get();
        $brands = ThuongHieu::orderBy('ten_thuong_hieu', 'asc')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    // 2. CREATE
    public function create()
    {
        $brands = ThuongHieu::orderBy('ten_thuong_hieu', 'asc')->get();
        $categories = DanhMuc::where('parent_id', '!=', 0)->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    // 3. STORE
    public function store(Request $request)
    {
        $request->validate([
            'ten_san_pham'   => 'required|max:255',
            'ma_sku'         => 'nullable|string|max:50|unique:san_pham,ma_sku',
            'id_danh_muc'    => 'required|exists:danh_muc,id',
            'id_thuong_hieu' => 'required|exists:thuong_hieu,id',
            'gia_ban'        => 'required|numeric|min:0', 
            'anh_dai_dien'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only([
    'ten_san_pham', 'id_danh_muc', 'id_thuong_hieu', 'ma_sku',
    'gia_ban', 'gia_khuyen_mai', 'so_luong_ton', 'mo_ta',
    'tinh_nang_noi_bat', 'uu_dai_khac', 
    'thong_tin_them', 
    
    // ... các trường khác
    'uu_dai_student', 
    'gia_thu_cu', 
    'tro_gia' 
]);
            
            $data['slug'] = Str::slug($request->ten_san_pham) . '-' . time();
            $data['is_flash_sale'] = $request->has('is_flash_sale') ? 1 : 0;
            $data['tra_gop_0_phan_tram'] = $request->has('tra_gop_0_phan_tram') ? 1 : 0;
            $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;
            $data['ngay_tao'] = now();
            $data['ngay_cap_nhat'] = now();

            if ($request->hasFile('anh_dai_dien')) {
                $path = $request->file('anh_dai_dien')->store('products', 'public');
                $data['anh_dai_dien'] = 'storage/' . $path;
            }

            if ($request->hasFile('video_product')) {
                $videoPath = $request->file('video_product')->store('videos', 'public');
                $data['video_url'] = 'storage/' . $videoPath;
            }

            $product = SanPham::create($data);
            $product->thongSo()->create(['id_san_pham' => $product->id]);

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    // 4. EDIT
    public function edit($id)
    {
        // SỬA DÒNG NÀY: Thêm sắp xếp cho 'cauHinh' và 'bienThe'
        $product = SanPham::with([
            'thongSo', 
            'hinhAnh', 
            'bienThe' => function($q) {
                $q->orderBy('gia_ban', 'asc'); // Sắp xếp màu theo giá tăng dần
            },
            'cauHinh' => function($q) {
                $q->orderBy('loai_cau_hinh', 'asc') // Gom nhóm theo loại (Adapter -> Chip -> RAM -> SSD)
                  ->orderBy('gia_them', 'asc');     // Trong nhóm thì xếp theo giá
            }
        ])->findOrFail($id);

        $categories = DanhMuc::where('parent_id', '!=', 0)->get();
        $brands = ThuongHieu::orderBy('ten_thuong_hieu', 'asc')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }
   // =================================================================
    // 5. UPDATE (QUAN TRỌNG: XỬ LÝ 3 ẢNH GỘP 1 DÒNG)
    // =================================================================
    public function update(Request $request, $id)
    {
        $product = SanPham::findOrFail($id);
        
        $request->validate([
            'ten_san_pham'    => 'required|max:255',
            'id_danh_muc'     => 'required',
            'gia_ban'         => 'required|numeric',
            'anh_dai_dien'    => 'nullable|image|max:2048',
            'video_product'   => 'nullable|mimes:mp4,mov,ogg,qt|max:50000',
            
            'anh_tinh_nang.*' => 'nullable|image|max:2048',
            
            // Validate mảng ảnh gallery
            'gallery_medium.*'=> 'nullable|image|max:2048', // Bắt buộc có ảnh thường
            'gallery_small.*' => 'nullable|image|max:2048',
            'gallery_large.*' => 'nullable|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // A. CẬP NHẬT THÔNG TIN CƠ BẢN
           // Trong hàm update()
$data = $request->only([
    'ten_san_pham', 'id_danh_muc', 'id_thuong_hieu', 'ma_sku',
    'gia_ban', 'gia_khuyen_mai', 'so_luong_ton', 'mo_ta',
    'tinh_nang_noi_bat', 'uu_dai_khac', 
    'thong_tin_them', // <--- THÊM VÀO ĐÂY NỮA
    
    'uu_dai_student', 
    'gia_thu_cu', 
    'tro_gia'
]);
            
            $data['slug'] = Str::slug($request->ten_san_pham) . '-' . $product->id;
            $data['is_flash_sale'] = $request->has('is_flash_sale') ? 1 : 0;
            $data['tra_gop_0_phan_tram'] = $request->has('tra_gop_0_phan_tram') ? 1 : 0;
            $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;
            $data['ngay_cap_nhat'] = now();

            // Ảnh đại diện
            if ($request->hasFile('anh_dai_dien')) {
                if ($product->anh_dai_dien) {
                    $oldPath = str_replace('storage/', '', $product->anh_dai_dien);
                    if (Storage::disk('public')->exists($oldPath)) Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('anh_dai_dien')->store('products', 'public');
                $data['anh_dai_dien'] = 'storage/' . $path;
            }

            // Video
            if ($request->hasFile('video_product')) {
                if ($product->video_url) {
                    $oldPath = str_replace('storage/', '', $product->video_url);
                    if (Storage::disk('public')->exists($oldPath)) Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file('video_product')->store('videos', 'public');
                $data['video_url'] = 'storage/' . $path;
            }

            $product->update($data);

            // B. CẬP NHẬT THÔNG SỐ KỸ THUẬT (Giữ nguyên logic JSON)
            $customSpecs = [];
            if ($request->has('custom_keys') && $request->has('custom_values')) {
                foreach ($request->custom_keys as $index => $key) {
                    if (!empty($key) && !empty($request->custom_values[$index])) {
                        $customSpecs[] = ['key' => $key, 'val' => $request->custom_values[$index]];
                    }
                }
            }

            $product->thongSo()->updateOrCreate(
                ['id_san_pham' => $id],
                [
                    'cong_nghe_cpu'       => $request->cong_nghe_cpu,
                    'so_nhan'             => $request->so_nhan,
                    'loai_card_do_hoa'    => $request->loai_card_do_hoa,
                    'ram'                 => $request->ram,
                    'o_cung'              => $request->o_cung,
                    'kich_thuoc_man_hinh' => $request->kich_thuoc_man_hinh,
                    'do_phan_giai'        => $request->do_phan_giai,
                    'cong_nghe_man_hinh'  => $request->cong_nghe_man_hinh,
                    'tan_so_quet'         => $request->tan_so_quet,
                    'tam_nen'             => $request->tam_nen,
                    'cong_giao_tiep'      => $request->cong_giao_tiep,
                    'ket_noi_khong_day'   => $request->ket_noi_khong_day,
                    'webcam'              => $request->webcam,
                    'he_dieu_hanh'        => $request->he_dieu_hanh,
                    'cong_nghe_am_thanh'  => $request->cong_nghe_am_thanh,
                    'chat_lieu'           => $request->chat_lieu,
                    'kich_thuoc'          => $request->kich_thuoc,
                    'trong_luong'         => $request->trong_luong,
                    'pin'                 => $request->pin,
                    'thong_so_tuy_chinh'  => $customSpecs, 
                ]
            );

            // C. XỬ LÝ MEDIA

            // 1. Ảnh Tính Năng (Feature) - Logic cũ (1 file 1 dòng)
            if ($request->hasFile('anh_tinh_nang')) {
                foreach ($request->file('anh_tinh_nang') as $file) {
                    $name = time() . '_feat_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('storage/products/feature'), $name);
                    HinhAnhSanPham::create([
                        'id_san_pham'   => $id,
                        'duong_dan_anh' => 'storage/products/feature/' . $name,
                        'loai'          => 'feature'
                    ]);
                }
            }

            // 2. ALBUM 3 CẤP ĐỘ (GALLERY) - LOGIC MỚI
            // Dùng vòng lặp dựa trên file Medium (vì bắt buộc phải có)
            if ($request->hasFile('gallery_medium')) {
                foreach ($request->file('gallery_medium') as $key => $fileMedium) {
                    
                    // a. Lưu ảnh Medium (Thường)
                    $nameMed = time() . '_med_' . uniqid() . '.' . $fileMedium->getClientOriginalExtension();
                    $fileMedium->move(public_path('storage/products/gallery'), $nameMed);
                    $pathMed = 'storage/products/gallery/' . $nameMed;

                    // b. Lưu ảnh Small (Nhỏ) - Nếu có ở cùng vị trí $key
                    $pathSmall = null;
                    if ($request->hasFile('gallery_small') && isset($request->file('gallery_small')[$key])) {
                        $fileSmall = $request->file('gallery_small')[$key];
                        $nameSmall = time() . '_small_' . uniqid() . '.' . $fileSmall->getClientOriginalExtension();
                        $fileSmall->move(public_path('storage/products/gallery'), $nameSmall);
                        $pathSmall = 'storage/products/gallery/' . $nameSmall;
                    }

                    // c. Lưu ảnh Large (Lớn) - Nếu có ở cùng vị trí $key
                    $pathLarge = null;
                    if ($request->hasFile('gallery_large') && isset($request->file('gallery_large')[$key])) {
                        $fileLarge = $request->file('gallery_large')[$key];
                        $nameLarge = time() . '_large_' . uniqid() . '.' . $fileLarge->getClientOriginalExtension();
                        $fileLarge->move(public_path('storage/products/gallery'), $nameLarge);
                        $pathLarge = 'storage/products/gallery/' . $nameLarge;
                    }

                    // d. Tạo bản ghi DB (1 dòng chứa 3 link)
                    HinhAnhSanPham::create([
                        'id_san_pham'   => $id,
                        'duong_dan_anh' => $pathMed,   // Cột chính
                        'anh_nho'       => $pathSmall, // Cột mới
                        'anh_lon'       => $pathLarge, // Cột mới
                        'loai'          => 'gallery'
                    ]);
                }
            }

            // --- CẬP NHẬT DỮ LIỆU CŨ (EDIT IN PLACE) ---
            
            // 1. Cập nhật Biến thể cũ (Màu sắc)
            if ($request->has('update_variant')) {
                foreach ($request->update_variant as $varId => $varData) {
                    $variant = BienTheSanPham::find($varId);
                    if ($variant) {
                        $variant->update([
                            'ten_mau' => $varData['ten_mau'],
                            'gia_ban' => $varData['gia_ban']
                        ]);
                        // Nếu có up ảnh thay thế cho màu cũ
                        if (isset($request->file('update_variant')[$varId]['anh_mau'])) {
                            $file = $request->file('update_variant')[$varId]['anh_mau'];
                            $name = time() . '_var_upd_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('storage/products/variants'), $name);
                            // Xóa ảnh cũ
                            if (file_exists(public_path($variant->anh_mau))) @unlink(public_path($variant->anh_mau));
                            $variant->update(['anh_mau' => 'storage/products/variants/' . $name]);
                        }
                    }
                }
            }

            // 2. Cập nhật Cấu hình cũ (RAM/SSD...)
            if ($request->has('update_config')) {
                foreach ($request->update_config as $confId => $confData) {
                    CauHinhSanPham::where('id', $confId)->update([
                        'loai_cau_hinh' => $confData['type'],
                        'ten_cau_hinh'  => $confData['name'],
                        'gia_them'      => $confData['price']
                    ]);
                }
            }

        // --- D. XỬ LÝ BIẾN THỂ SẢN PHẨM (MỚI THÊM) ---
            if ($request->has('variant_color')) {
                foreach ($request->variant_color as $key => $colorName) {
                    // Kiểm tra dữ liệu đầu vào có đủ không
                    if (!empty($colorName) && !empty($request->variant_price[$key])) {
                        
                        $imagePath = null;
                        // Upload ảnh biến thể
                        if ($request->hasFile('variant_image') && isset($request->file('variant_image')[$key])) {
                            $fileVar = $request->file('variant_image')[$key];
                            $nameVar = time() . '_var_' . uniqid() . '.' . $fileVar->getClientOriginalExtension();
                            $fileVar->move(public_path('storage/products/variants'), $nameVar);
                            $imagePath = 'storage/products/variants/' . $nameVar;
                        }

                        // Lưu vào bảng 'bien_the_san_pham'
                        if ($imagePath) {
                            BienTheSanPham::create([
                                'id_san_pham' => $id,
                                'ten_mau'     => $colorName,
                                'gia_ban'     => $request->variant_price[$key],
                                'anh_mau'     => $imagePath
                            ]);
                        }
                    }
                }
            }

            // --- E. XỬ LÝ CẤU HÌNH NÂNG CẤP ---
if ($request->has('conf_type')) {
    foreach ($request->conf_type as $k => $type) {
        if (!empty($request->conf_name[$k])) {
            // Xử lý checkbox mặc định (mảng checkbox hơi phức tạp, đây là cách đơn giản hóa)
            // Lưu ý: Checkbox HTML không gửi value nếu không được check, cần xử lý kỹ hơn ở JS hoặc đặt index ẩn.
            // Ở đây mình giả định bạn nhập đúng.
            
            \App\Models\CauHinhSanPham::create([
                'id_san_pham'   => $id,
                'loai_cau_hinh' => $type,
                'ten_cau_hinh'  => $request->conf_name[$k],
                'gia_them'      => $request->conf_price[$k] ?? 0,
                'la_mac_dinh'   => 0 // Tạm thời để 0, xử lý logic mặc định nâng cao sau
            ]);
        }
    }
}

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật: ' . $e->getMessage());
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    // 6. DESTROY
    public function destroy($id)
    {
        $product = SanPham::with('hinhAnh')->findOrFail($id);
        
        if ($product->anh_dai_dien) {
            $path = str_replace('storage/', '', $product->anh_dai_dien);
            if (Storage::disk('public')->exists($path)) Storage::disk('public')->delete($path);
        }
        if ($product->video_url) {
            $path = str_replace('storage/', '', $product->video_url);
            if (Storage::disk('public')->exists($path)) Storage::disk('public')->delete($path);
        }
        if ($product->hinhAnh) {
            foreach ($product->hinhAnh as $img) {
                $path = str_replace('storage/', '', $img->duong_dan_anh);
                if (Storage::disk('public')->exists($path)) Storage::disk('public')->delete($path);
                $img->delete();
            }
        }
        $product->delete();
        return back()->with('success', 'Đã xóa sản phẩm thành công.');
    }

    // 7. UPDATE STATUS
    public function updateStatus(Request $request)
    {
        try {
            $product = SanPham::findOrFail($request->id);
            $product->trang_thai = $request->trang_thai;
            $product->ngay_cap_nhat = now();
            $product->save(); 
            return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 8. SHOW
    public function show($id)
    {
        $product = SanPham::with(['danhMuc', 'thuongHieu', 'thongSo', 'hinhAnh'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    // 9. DELETE IMAGE
    public function deleteImage($imageId)
    {
        try {
            $img = HinhAnhSanPham::findOrFail($imageId);
            if (file_exists(public_path($img->duong_dan_anh))) {
                @unlink(public_path($img->duong_dan_anh));
            }
            $img->delete();
            return response()->json(['success' => true, 'message' => 'Đã xóa ảnh']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi xóa ảnh'], 500);
        }
    }

    // Xóa Video Sản phẩm
    public function deleteVideo($productId)
    {
        try {
            $product = SanPham::findOrFail($productId);
            
            if ($product->video_url) {
                // Xóa file vật lý
                $path = str_replace('storage/', '', $product->video_url);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                
                // Cập nhật DB về null
                $product->video_url = null;
                $product->save();
            }
            
            return response()->json(['success' => true, 'message' => 'Đã xóa video!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi xóa video'], 500);
        }
    }

    public function deleteVariant($id)
    {
        try {
            $variant = BienTheSanPham::findOrFail($id);
            // Xóa file ảnh cũ
            if (file_exists(public_path($variant->anh_mau))) {
                @unlink(public_path($variant->anh_mau));
            }
            $variant->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteConfig($id)
    {
        try {
            CauHinhSanPham::destroy($id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}