@extends('admin.layouts.main')
@section('title', 'Cập nhật người dùng')

@section('content')
    <div class="text-center mb-8">
        {{-- Ảnh đại diện (Tạm dùng API avatar theo tên người dùng) --}}
        <img id="previewAvatar" 
             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6b7280&color=fff&bold=true"
             alt="Avatar Preview" class="preview-avatar mx-auto" />
        <h3 class="mt-2 font-bold text-gray-700">ID: #{{ $user->id }}</h3>
    </div>

    {{-- Form cập nhật --}}
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT') {{-- Bắt buộc để Laravel hiểu đây là Update --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Họ tên --}}
            <div class="input-group">
                <label>Họ tên <span class="text-red-500">*</span></label>
                {{-- Ưu tiên lấy old (khi lỗi), nếu không có thì lấy từ DB --}}
                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nguyễn Văn A"
                    class="w-full px-4 py-2 border rounded-lg" autocomplete="off" />
                @error('name')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="input-group">
                <label>Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="email@example.com"
                    class="w-full px-4 py-2 border rounded-lg" autocomplete="off" />
                @error('email')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            {{-- Số điện thoại --}}
            <div class="input-group">
                <label>Số điện thoại</label>
                <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}" placeholder="0901234567"
                    class="w-full px-4 py-2 border rounded-lg" autocomplete="off" />
                @error('so_dien_thoai')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            {{-- Mật khẩu (Xử lý đặc biệt) --}}
            <div class="input-group">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" placeholder="********" class="w-full px-4 py-2 border rounded-lg"
                    autocomplete="new-password" />
                <p class="text-xs text-gray-500 mt-1 italic">* Bỏ trống nếu muốn giữ nguyên mật khẩu cũ</p>
                @error('password')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            {{-- Vai trò --}}
            <div class="input-group">
                <label>Vai trò <span class="text-red-500">*</span></label>
                <select name="id_vai_tro" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">-- Chọn vai trò --</option>
                    {{-- Logic chọn: Nếu có old thì dùng old, không thì dùng giá trị từ DB --}}
                    <option value="2" {{ (old('id_vai_tro') ?? $user->id_vai_tro) == 2 ? 'selected' : '' }}>Khách hàng</option>
                    <option value="1" {{ (old('id_vai_tro') ?? $user->id_vai_tro) == 1 ? 'selected' : '' }}>Admin (Quản trị)</option>
                </select>
                @error('id_vai_tro')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            {{-- Trạng thái --}}
            <div class="input-group">
                <label>Trạng thái <span class="text-red-500">*</span></label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg">
                    <option value="1" {{ (old('status') ?? $user->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ (old('status') ?? $user->status) == 0 ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>
        </div>

        {{-- Địa chỉ --}}
        <div class="input-group mt-6">
            <label>Địa chỉ</label>
            <textarea name="dia_chi" rows="3" class="w-full px-4 py-2 border rounded-lg" 
                placeholder="Nhập địa chỉ...">{{ old('dia_chi', $user->dia_chi) }}</textarea>
        </div>

        {{-- Nút bấm --}}
        <div class="flex justify-center gap-4 mt-8">
            <a href="{{ route('admin.users.index') }}" class="btn btn-warning px-8 py-2 rounded">Hủy</a>
            <button type="submit" class="btn btn-success text-lg px-10 py-2 rounded bg-green-600 text-white">
                <i class="fas fa-save"></i> Cập nhật
            </button>
        </div>
    </form>
@endsection