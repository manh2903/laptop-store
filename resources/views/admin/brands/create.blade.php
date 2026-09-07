@extends('admin.layouts.main')
@section('title', 'Thêm Thương Hiệu')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6 pb-10">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Thêm Thương Hiệu Mới</h3>
            <a href="{{ route('admin.brands.index') }}" class="text-gray-500 hover:text-indigo-600 text-sm font-medium transition flex items-center">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="space-y-6">
                {{-- Tên thương hiệu --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tên thương hiệu <span class="text-red-500">*</span></label>
                    <input type="text" name="ten_thuong_hieu" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="VD: Asus, Dell, Apple..." required>
                </div>

                {{-- Thứ tự hiển thị --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thứ tự hiển thị</label>
                    <input type="number" name="thu_tu_sap_xep" value="0" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                {{-- Trạng thái với Công tắc bo góc --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <i class="fas fa-eye text-indigo-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Trạng thái hiển thị</p>
                            <p class="text-xs text-gray-500">Bật để hãng hiện trên trang chủ</p>
                        </div>
                    </div>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="trang_thai" value="1" class="sr-only peer" checked>
                        <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>

                {{-- Upload Logo --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Logo thương hiệu</label>
                    <div class="flex flex-col items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-indigo-50 transition border-indigo-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-4xl text-indigo-400 mb-3"></i>
                                <p class="text-sm text-gray-500 font-medium">Click để tải ảnh lên</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG (Tỷ lệ 1:1 là tốt nhất)</p>
                            </div>
                            <input type="file" name="hinh_anh" class="hidden" onchange="previewImage(this)">
                        </label>
                        {{-- Vùng xem trước ảnh --}}
                        <div id="preview-container" class="hidden mt-4">
                            <img id="preview" class="h-24 w-24 object-contain rounded-lg border shadow-sm p-2 bg-white">
                        </div>
                    </div>
                </div>

                {{-- Nút lưu --}}
                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-10 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-save mr-2"></i> Tạo Thương Hiệu
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const container = document.getElementById('preview-container');
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