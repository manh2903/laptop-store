@extends('admin.layouts.main')
@section('title', 'Thêm Mới Danh Mục')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6 pb-10">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Thêm Mới Danh Mục</h3>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-indigo-600 text-sm font-medium transition">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- CỘT TRÁI --}}
                <div class="space-y-6">
                    {{-- 1. LOẠI DANH MỤC (Đã bỏ Hãng) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3">Loại danh mục <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-4"> {{-- Chia 2 cột thay vì 3 --}}
                            <label class="cursor-pointer">
                                <input type="radio" name="loai" value="menu" class="peer sr-only" onchange="updateFormUI()" {{ $selectedType == 'menu' ? 'checked' : '' }}>
                                <div class="p-3 text-center border rounded-lg bg-white peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 hover:bg-gray-50 transition shadow-sm h-full flex flex-col justify-center items-center">
                                    <i class="fas fa-bars block mb-1 text-lg"></i> <span class="font-medium text-sm">Menu Đa Cấp</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="loai" value="need" class="peer sr-only" onchange="updateFormUI()" {{ $selectedType == 'need' ? 'checked' : '' }}>
                                <div class="p-3 text-center border rounded-lg bg-white peer-checked:bg-teal-50 peer-checked:border-teal-500 peer-checked:text-teal-700 hover:bg-gray-50 transition shadow-sm h-full flex flex-col justify-center items-center">
                                    <i class="fas fa-filter block mb-1 text-lg"></i> <span class="font-medium text-sm">Nhu Cầu (Gaming, VP...)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. TÊN DANH MỤC --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                        <input type="text" name="ten_danh_muc" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Nhập tên..." required>
                    </div>

                    {{-- 3. DANH MỤC CHA --}}
                    <div id="box_parent" class="hidden">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Danh mục cha</label>
                        <select name="parent_id" id="input_parent" onchange="updateFormUI()" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <option value="0">⭐ Là Danh Mục Gốc (Ông)</option>
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}">{{ $p->ten_danh_muc }}</option>
                                @foreach($p->children as $child)
                                    <option value="{{ $child->id }}">&nbsp;&nbsp;&nbsp;└── {{ $child->ten_danh_muc }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div class="space-y-6 bg-gray-50/50 p-6 rounded-xl border border-dashed border-gray-200">
                    
                    {{-- 4. THỨ TỰ --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="thu_tu" value="0" class="w-24 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    {{-- 5. ICON --}}
                    <div id="box_icon" class="hidden">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Icon (FontAwesome)</label>
                        <div class="flex shadow-sm">
                            <span class="inline-flex items-center px-4 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-500">
                                <i class="fas fa-icons"></i>
                            </span>
                            <input type="text" name="icon" class="w-full border border-gray-300 rounded-r-lg p-3 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="VD: fas fa-laptop">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Chỉ dành cho Menu Gốc.</p>
                    </div>

                    {{-- 6. HÌNH ẢNH --}}
                    <div id="box_image" class="hidden">
                        <label id="label_image" class="block text-sm font-bold text-gray-700 mb-3">Hình ảnh</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-indigo-50 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Click để tải ảnh</p>
                                </div>
                                <input type="file" name="hinh_anh" class="hidden" onchange="previewImage(this)">
                            </label>
                        </div>
                        <img id="preview" class="hidden mt-4 h-20 w-auto rounded border shadow-sm mx-auto p-1 bg-white">
                    </div>
                </div>
            </div>

            {{-- 7. TRẠNG THÁI --}}
            <div class="mt-8 pt-6 border-t border-gray-100">
                <label class="block text-sm font-bold text-gray-700 mb-3">Trạng thái hiển thị</label>
                <div class="flex gap-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="trang_thai" value="1" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" {{ old('trang_thai', '1') == '1' ? 'checked' : '' }}>
                        <span class="ml-3 text-gray-700 font-medium group-hover:text-indigo-600 transition"><i class="fas fa-check-circle text-green-500 mr-2"></i>Hiển thị công khai</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer group">
                        <input type="radio" name="trang_thai" value="0" class="w-5 h-5 text-red-600 focus:ring-red-500 border-gray-300" {{ old('trang_thai') == '0' ? 'checked' : '' }}>
                        <span class="ml-3 text-gray-700 font-medium group-hover:text-red-600 transition"><i class="fas fa-times-circle text-gray-400 mr-2"></i>Ẩn tạm thời</span>
                    </label>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-plus mr-2"></i> Tạo Danh Mục
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT: Bỏ logic xử lý Brand --}}
<script>
    function updateFormUI() {
        let type = 'menu';
        try {
            const checkedRadio = document.querySelector('input[name="loai"]:checked');
            if (checkedRadio) type = checkedRadio.value;
        } catch (e) {}

        const parentSelect = document.getElementById('input_parent');
        const parentId = parentSelect ? parentSelect.value : 0;
        
        const boxParent = document.getElementById('box_parent');
        const boxIcon   = document.getElementById('box_icon');
        const boxImage  = document.getElementById('box_image');
        const labelImage = document.getElementById('label_image');

        if (!boxParent || !boxIcon || !boxImage) return;

        // Reset
        boxParent.style.display = 'none';
        boxIcon.style.display   = 'none';
        boxImage.style.display  = 'none';

        if (type === 'menu') {
            boxParent.style.display = 'block';
            if (parentId == 0) { 
                boxIcon.style.display = 'block';
            }
        } else if (type === 'need') {
            boxImage.style.display = 'block';    
            if(labelImage) labelImage.innerText = "Ảnh đại diện Nhu cầu";
        }
    }

    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateFormUI();
    });

    const radios = document.querySelectorAll('input[name="loai"]');
    radios.forEach(radio => {
        radio.addEventListener('change', updateFormUI);
    });

    const parentInput = document.getElementById('input_parent');
    if(parentInput) {
        parentInput.addEventListener('change', updateFormUI);
    }
</script>
@endsection