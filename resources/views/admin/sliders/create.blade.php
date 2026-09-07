@extends('admin.layouts.main')
@section('title', 'Thêm mới Slider')

@section('content')
    <div class="flex justify-between items-center mb-4">
        {{-- Dùng class .page-title trong CSS để có màu gradient đẹp --}}
        <h2 class="page-title">Thêm mới Slider / Banner</h2>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- Dùng class .card để có khung trắng, bóng đổ, bo góc chuẩn --}}
    <div class="card">
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Hệ thống Grid này đã có trong CSS của bạn --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- CỘT TRÁI --}}
                <div>
                    {{-- Tên Slider --}}
                    <div class="input-group">
                        <label>Tên Slider <span style="color: red">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               placeholder="Ví dụ: Banner Tết 2025...">
                        @error('name')
                            <p style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Đường dẫn --}}
                    <div class="input-group">
                        <label>Đường dẫn khi click (Link)</label>
                        <input type="text" name="link" value="{{ old('link') }}" 
                               placeholder="https://... hoặc để trống">
                    </div>

                    {{-- Danh mục --}}
                    <div class="input-group">
                        <label>Hiển thị tại trang nào?</label>
                        <select name="category_id">
                            <option value="">-- Trang chủ (Mặc định) --</option>
                            @foreach ($categories as $cate)
                                <option value="{{ $cate->id }}" {{ old('category_id') == $cate->id ? 'selected' : '' }}>
                                    Danh mục: {{ $cate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div>
                    {{-- Vị trí --}}
                    <div class="input-group">
                        <label>Vị trí hiển thị <span style="color: red">*</span></label>
                        <select name="position">
                            <option value="main" {{ old('position') == 'main' ? 'selected' : '' }}>Slider Chính (To, Chạy ngang)</option>
                            <option value="right_top" {{ old('position') == 'right_top' ? 'selected' : '' }}>Banner Phải (Trên)</option>
                            <option value="right_bottom" {{ old('position') == 'right_bottom' ? 'selected' : '' }}>Banner Phải (Dưới)</option>
                        </select>
                        @error('position')
                            <p style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Thứ tự & Trạng thái (Chia đôi cột bằng Grid lồng nhau) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label>Thứ tự</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                        </div>
                        <div class="input-group">
                            <label>Trạng thái</label>
                            <select name="status">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm ẩn</option>
                            </select>
                        </div>
                    </div>

                    {{-- Upload Ảnh --}}
                    <div class="input-group">
                        <label>Hình ảnh <span style="color: red">*</span></label>
                        {{-- Style inline nhẹ để tạo khung dashed giả lập Tailwind --}}
                        <div style="border: 2px dashed #cbd5e1; padding: 20px; border-radius: 12px; background: #f8fafc; text-align: center;">
                            <input type="file" name="images[]" multiple accept="image/*" 
                                   style="border: none; background: transparent; box-shadow: none; width: 100%;">
                            <p style="color: #64748b; font-size: 13px; margin-top: 8px;">
                                <i class="fas fa-info-circle"></i> Giữ phím <strong>Ctrl</strong> để chọn nhiều ảnh.
                            </p>
                        </div>
                        @error('images')
                            <p style="color: red; font-size: 13px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Nút bấm --}}
            <div class="flex justify-end gap-4 mt-6">
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-danger">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Slider
                </button>
            </div>
        </form>
    </div>
@endsection