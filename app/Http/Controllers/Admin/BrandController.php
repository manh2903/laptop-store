<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThuongHieu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    // 1. Danh sách Hãng
    public function index()
    {
        // Lấy danh sách, sắp xếp theo thứ tự ưu tiên
        $brands = ThuongHieu::orderBy('thu_tu_sap_xep', 'asc')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    // 2. Giao diện Thêm mới
    public function create()
    {
        return view('admin.brands.create');
    }

    // 3. Xử lý Lưu Hãng mới
    public function store(Request $request)
    {
        $request->validate([
            'ten_thuong_hieu' => 'required|max:255',
            'hinh_anh' => 'nullable|image|max:2048', // Ảnh tối đa 2MB
        ]);

        $data = $request->all();
        
        // Tạo slug tự động
        $data['slug'] = Str::slug($request->ten_thuong_hieu);
        
        // Upload Logo
        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('brands', 'public');
            $data['hinh_anh'] = 'storage/' . $path; // Lưu đường dẫn
        }
        
        // Mặc định thứ tự là 0 nếu không nhập
        $data['thu_tu_sap_xep'] = $request->thu_tu_sap_xep ?? 0;
        
        // Xử lý trạng thái từ công tắc toggle
        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        ThuongHieu::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm hãng mới thành công!');
    }

    // 4. Giao diện Sửa
    public function edit($id)
    {
        $brand = ThuongHieu::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    // 5. Xử lý Cập nhật
    public function update(Request $request, $id)
    {
        $brand = ThuongHieu::findOrFail($id);
        
        $request->validate([
            'ten_thuong_hieu' => 'required|max:255',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->ten_thuong_hieu);

        // Xử lý ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ để nhẹ server
            if ($brand->hinh_anh) {
                $oldPath = str_replace('storage/', '', $brand->hinh_anh);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('hinh_anh')->store('brands', 'public');
            $data['hinh_anh'] = 'storage/' . $path;
        }

        // Xử lý trạng thái từ công tắc toggle
        $data['trang_thai'] = $request->has('trang_thai') ? 1 : 0;

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật hãng thành công!');
    }

    // 6. Xóa Hãng
    public function destroy($id)
    {
        $brand = ThuongHieu::findOrFail($id);
        
        // Xóa file ảnh vật lý trước khi xóa bản ghi
        if ($brand->hinh_anh) {
            $oldPath = str_replace('storage/', '', $brand->hinh_anh);
            Storage::disk('public')->delete($oldPath);
        }

        $brand->delete();
        return back()->with('success', 'Đã xóa hãng thành công!');
    }

    /**
     * Cập nhật trạng thái nhanh qua Ajax
     * Trả về cả thời gian cập nhật để hiển thị lên giao diện
     */
    public function updateStatus(Request $request)
    {
        try {
            $brand = ThuongHieu::findOrFail($request->id);
            $brand->trang_thai = $request->trang_thai;
            
            // Lưu và tự động cập nhật cột 'ngay_cap_nhat'
            $brand->save(); 

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công!',
                // Trả về định dạng ngày giờ để hiển thị ngay không cần F5
                'new_date' => $brand->ngay_cap_nhat->format('H:i d/m/Y')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}