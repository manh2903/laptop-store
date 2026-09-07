<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use App\Models\SanPham; // Import SanPham để dùng trong hàm delete
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // =========================================================
    // 1. DANH SÁCH (INDEX) - LỌC BỎ THƯƠNG HIỆU
    // =========================================================
    public function index(Request $request)
    {
        // 1. Lấy tham số 'type' từ URL (mặc định là 'all')
        $type = $request->query('type', 'all'); 

        // 2. Query cơ bản
        $query = DanhMuc::query();

        // 3. Logic Lọc:
        if ($type != 'all') {
            // Nếu chọn type cụ thể (menu, need) thì lọc theo nó
            $query->where('loai', $type);
        } else {
            // Mặc định (all): Lấy tất cả NHƯNG TRỪ 'brand' ra
            $query->where('loai', '!=', 'brand');
        }

        // 4. Sắp xếp
        $categories = $query->orderBy('loai', 'asc')
                            ->orderBy('parent_id', 'asc')
                            ->orderBy('thu_tu', 'asc')
                            ->orderBy('id', 'desc')
                            ->paginate(20);

        // 5. Thống kê (Chỉ còn Total, Menu, Need, Hidden - Đã bỏ Brand)
        $stats = [
            'total'  => DanhMuc::where('loai', '!=', 'brand')->count(), // Tổng không tính brand
            'menu'   => DanhMuc::where('loai', 'menu')->count(),
            // Đã xóa 'brand'
            'need'   => DanhMuc::where('loai', 'need')->count(),
            'hidden' => DanhMuc::where('trang_thai', 0)->where('loai', '!=', 'brand')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'type', 'stats'));
    }

    // =========================================================
    // 2. FORM THÊM MỚI (CREATE)
    // =========================================================
    public function create(Request $request)
    {
        $selectedType = $request->query('type', 'menu');

        // Lấy danh sách cha (Chỉ Menu mới có cha)
        $parents = DanhMuc::where('loai', 'menu')
                          ->where('parent_id', 0)
                          ->with('children')
                          ->orderBy('thu_tu', 'asc')
                          ->get();

        return view('admin.categories.create', compact('selectedType', 'parents'));
    }

    // =========================================================
    // 3. XỬ LÝ LƯU (STORE)
    // =========================================================
    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required|max:255',
            'loai'         => 'required|in:menu,need', // Bỏ 'brand' khỏi validation
            'hinh_anh'     => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048|nullable',
            'thu_tu'       => 'nullable|integer',
        ], [
            'ten_danh_muc.required' => 'Tên danh mục không được để trống.',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->ten_danh_muc);

        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('categories', 'public');
            $data['hinh_anh'] = $path;
        }

        if ($data['loai'] != 'menu') {
            $data['parent_id'] = 0;
        }

        $data['trang_thai'] = $request->input('trang_thai', 0);
        $data['thu_tu'] = $request->input('thu_tu', 0);

        DanhMuc::create($data);

        return redirect()->route('admin.categories.index', ['type' => $data['loai']])
                         ->with('success', 'Thêm danh mục mới thành công!');
    }

    // =========================================================
    // 4. FORM SỬA (EDIT)
    // =========================================================
    public function edit($id)
    {
        $category = DanhMuc::findOrFail($id);
        
        $parents = DanhMuc::where('id', '!=', $id)
                          ->where('loai', 'menu')
                          ->where('parent_id', 0)
                          ->with('children')
                          ->orderBy('thu_tu', 'asc')
                          ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    // =========================================================
    // 5. XỬ LÝ CẬP NHẬT (UPDATE)
    // =========================================================
    public function update(Request $request, $id)
    {
        $category = DanhMuc::findOrFail($id);

        $request->validate([
            'ten_danh_muc' => 'required|max:255',
            'loai'         => 'required',
            'hinh_anh'     => 'image|mimes:jpeg,png,jpg,webp|max:2048|nullable',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->ten_danh_muc);

        if ($request->hasFile('hinh_anh')) {
            if ($category->hinh_anh && Storage::disk('public')->exists($category->hinh_anh)) {
                Storage::disk('public')->delete($category->hinh_anh);
            }
            $path = $request->file('hinh_anh')->store('categories', 'public');
            $data['hinh_anh'] = $path;
        }

        if ($data['loai'] != 'menu') {
            $data['parent_id'] = 0;
        }
        
        $data['trang_thai'] = $request->input('trang_thai', 0);

        $category->update($data);

        return redirect()->route('admin.categories.index', ['type' => $data['loai']])
                         ->with('success', 'Cập nhật danh mục thành công!');
    }

    // =========================================================
    // 6. XÓA (DESTROY)
    // =========================================================
    public function destroy($id)
    {
        $category = DanhMuc::findOrFail($id);
        
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Không thể xóa! Danh mục này đang chứa danh mục con.');
        }

        $productCount = SanPham::where('id_danh_muc', $id)->count();
        
        if ($productCount > 0) {
            return back()->with('error', "Không thể xóa! Danh mục này đang chứa $productCount sản phẩm.");
        }

        if ($category->hinh_anh && Storage::disk('public')->exists($category->hinh_anh)) {
            Storage::disk('public')->delete($category->hinh_anh);
        }

        $category->delete();

        return back()->with('success', 'Đã xóa danh mục thành công.');
    }

    public function updateStatus(Request $request)
{
    try {
        $category = \App\Models\DanhMuc::findOrFail($request->id);
        $category->trang_thai = $request->trang_thai;
        $category->save(); // Tự động cập nhật 'ngay_cap_nhat'

        // Lấy số lượng đang ẩn mới nhất để cập nhật Card thống kê
        $hiddenCount = \App\Models\DanhMuc::where('trang_thai', 0)->count();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái danh mục thành công!',
            'new_date' => $category->ngay_cap_nhat->format('H:i:s d/m/Y'),
            'hidden_count' => $hiddenCount // Trả về để Ajax cập nhật Card
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}
}