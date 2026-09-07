@extends('admin.layouts.main')
@section('title', 'Chỉnh sửa Slider')

@section('content')
    <div class="flex justify-between items-center mb-4">
        {{-- Dùng class .page-title cho đẹp --}}
        <h2 class="page-title">Chỉnh sửa Slider / Banner</h2>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- Dùng class .card của CSS thay cho bg-white rounded-lg... --}}
    <div class="card">
        {{-- Route sửa thành admin.slider.update (số ít) --}}
        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Grid layout giữ nguyên vì CSS bạn có hỗ trợ grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- CỘT TRÁI --}}
                <div>
                    {{-- Tên Slider --}}
                    <div class="input-group">
                        <label>Tên Slider <span style="color: red">*</span></label>
                        {{-- Bỏ form-control, style sẽ ăn theo .input-group input --}}
                        <input type="text" name="name" value="{{ old('name', $slider->name) }}">
                        @error('name')
                            <p style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Đường dẫn --}}
                    <div class="input-group">
                        <label>Đường dẫn (Link)</label>
                        <input type="text" name="link" value="{{ old('link', $slider->link) }}">
                    </div>

                    {{-- Danh mục --}}
                    <div class="input-group">
                        <label>Hiển thị tại trang nào?</label>
                        <select name="category_id">
                            <option value="">-- Trang chủ (Mặc định) --</option>
                            @foreach ($categories as $cate)
                                <option value="{{ $cate->id }}" 
                                    {{ old('category_id', $slider->category_id) == $cate->id ? 'selected' : '' }}>
                                    Danh mục: {{ $cate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hiển thị ảnh hiện tại (Style custom nhẹ cho đẹp) --}}
                    <div class="input-group">
                        <label>Ảnh hiện tại:</label>
                        <div style="padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: center; background: #f8fafc;">
                            <img src="{{ asset('uploads/slider/' . $slider->image) }}" 
                                 alt="Ảnh hiện tại" 
                                 style="max-height: 150px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div>
                    {{-- Vị trí --}}
                    <div class="input-group">
                        <label>Vị trí hiển thị <span style="color: red">*</span></label>
                        <select name="position">
                            <option value="main" {{ old('position', $slider->position) == 'main' ? 'selected' : '' }}>Slider Chính (To)</option>
                            <option value="right_top" {{ old('position', $slider->position) == 'right_top' ? 'selected' : '' }}>Banner Phải (Trên)</option>
                            <option value="right_bottom" {{ old('position', $slider->position) == 'right_bottom' ? 'selected' : '' }}>Banner Phải (Dưới)</option>
                        </select>
                    </div>

                    {{-- Thứ tự & Trạng thái --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label>Thứ tự</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}">
                        </div>
                        <div class="input-group">
                            <label>Trạng thái</label>
                            <select name="status">
                                <option value="1" {{ old('status', $slider->status) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('status', $slider->status) == '0' ? 'selected' : '' }}>Tạm ẩn</option>
                            </select>
                        </div>
                    </div>

                    {{-- Upload Ảnh Mới --}}
                    <div class="input-group">
                        <label>Thay đổi hình ảnh</label>
                        <div style="border: 2px dashed #cbd5e1; padding: 20px; border-radius: 12px; background: #f8fafc; text-align: center;">
                            {{-- Lưu ý: Edit thì name="image" (số ít), không có multiple --}}
                            <input type="file" name="image" accept="image/*" 
                                   style="width: 100%; background: transparent; border: none; box-shadow: none;">
                            
                            <p style="color: #ea580c; font-size: 13px; margin-top: 8px; font-weight: 500;">
                                <i class="fas fa-exclamation-triangle"></i> Chỉ chọn nếu bạn muốn thay thế ảnh cũ.
                            </p>
                        </div>
                        @error('image')
                            <p style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-end gap-4 mt-6">
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-danger">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
@endsection