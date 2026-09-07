@extends('admin.layouts.main')
@section('title', 'Thêm mới người dùng')

@section('content')
    <div class="text-center mb-8">
        <img id="previewAvatar" src="https://ui-avatars.com/api/?name=New+User&background=6b7280&color=fff&bold=true"
            alt="Avatar Preview" class="preview-avatar mx-auto" />
    </div>
    <form action="{{ route('admin.users.store') }}" method="POST" novalidate>
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="input-group">
                <label>Họ tên <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nguyễn Văn A"
                    class="w-full px-4 py-2 border rounded-lg" autocomplete="off" />
                @error('name')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label>Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com"
                    class="w-full px-4 py-2 border rounded-lg" autocomplete="off" />
                @error('email')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label>Số điện thoại</label>

                <input type="text" name="so_dien_thoai"
                    class="!w-full !px-4 !py-2 !border !rounded-lg focus:!border-blue-500 focus:!ring-2"
                    placeholder="095768****" maxlength="10" pattern="0[0-9]{9}"
                    title="Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0"
                    value="{{ old('so_dien_thoai', $user->so_dien_thoai ?? '') }}">

                @error('so_dien_thoai')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <div class="input-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="********" class="w-full px-4 py-2 border rounded-lg"
                    autocomplete="new-password" />
                @error('password')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label>Vai trò <span class="text-red-500">*</span></label>
                <select name="id_vai_tro" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">-- Chọn vai trò --</option>
                    <option value="2" {{ old('id_vai_tro') == '2' ? 'selected' : '' }}>Khách hàng</option>
                    <option value="1" {{ old('id_vai_tro') == '1' ? 'selected' : '' }}>Admin (Quản trị)</option>
                </select>
                @error('id_vai_tro')
                    <div class="flex items-center mt-2 text-red-600 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label>Trạng thái <span class="text-red-500">*</span></label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg">
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tạm khóa</option>
                </select>
            </div>

            <div class="input-group col-span-2">
                <label>Địa chỉ</label>
                <textarea name="dia_chi" rows="3" class="w-full px-4 py-2 border rounded-lg" placeholder="Nhập địa chỉ...">{{ old('dia_chi') }}</textarea>
            </div>
        </div>
        <div class="flex justify-center gap-4 mt-8 col-span-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-warning px-8 py-2 rounded">Hủy</a>
            <button type="submit" class="btn btn-success text-lg px-10 py-2 rounded bg-green-600 text-white">
                <i class="fas fa-save"></i> Lưu người dùng
            </button>
        </div>
    </form>
@endsection
