@extends('admin.layouts.main')
@section('title', 'Cập Nhật Thương Hiệu')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6 pb-10">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Chỉnh Sửa Thương Hiệu</h3>
            <a href="{{ route('admin.brands.index') }}" class="text-gray-500 hover:text-indigo-600 text-sm font-medium transition flex items-center">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                {{-- Tên thương hiệu --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tên thương hiệu <span class="text-red-500">*</span></label>
                    <input type="text" name="ten_thuong_hieu" value="{{ $brand->ten_thuong_hieu }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none transition" required>
                </div>

                {{-- Thứ tự hiển thị --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thứ tự hiển thị</label>
                    <input type="number" name="thu_tu_sap_xep" value="{{ $brand->thu_tu_sap_xep }}" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Trạng thái với Công tắc bo góc (Dữ liệu từ CSDL) --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <i class="fas fa-sync-alt text-indigo-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Trạng thái hoạt động</p>
                            <p class="text-xs text-gray-500">Tắt để ẩn hãng khỏi danh sách lọc trên trang chủ</p>
                        </div>
                    </div>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        {{-- Kiểm tra dữ liệu từ bảng thuong_hieu --}}
                        <input type="checkbox" name="trang_thai" value="1" class="sr-only peer" {{ $brand->trang_thai ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                {{-- Logo hiện tại & Thay đổi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Thay đổi Logo</label>
                    <div class="flex items-start gap-6">
                        {{-- Logo cũ --}}
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-2">Hiện tại</p>
                            <div class="h-24 w-24 rounded-lg border bg-white p-2 flex items-center justify-center shadow-inner">
                                @if($brand->hinh_anh)
                                    <img src="{{ asset($brand->hinh_anh) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <span class="text-gray-300 text-xs text-center font-mono">N/A</span>
                                @endif
                            </div>
                        </div>

                        {{-- Upload mới --}}
                        <div class="flex-1">
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-2">Tải lên logo mới</p>
                            <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-200 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-indigo-50 transition border-indigo-100">
                                <i class="fas fa-camera text-gray-400"></i>
                                <span class="text-xs text-gray-500 mt-1">Chọn file ảnh</span>
                                <input type="file" name="hinh_anh" class="hidden" onchange="previewImageEdit(this)">
                            </label>
                        </div>

                        {{-- Preview mới --}}
                        <div id="preview-edit-container" class="hidden text-center">
                            <p class="text-[10px] text-indigo-400 uppercase font-bold mb-2">Xem trước</p>
                            <div class="h-24 w-24 rounded-lg border border-indigo-200 bg-white p-2 flex items-center justify-center shadow-sm">
                                <img id="preview-edit" class="max-h-full max-w-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-10 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-check-circle mr-2"></i> Lưu Cập Nhật
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImageEdit(input) {
        const preview = document.getElementById('preview-edit');
        const container = document.getElementById('preview-edit-container');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection