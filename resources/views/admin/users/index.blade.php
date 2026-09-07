@extends('admin.layouts.main')
@section('title', 'Quản lý người dùng')
@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Danh sách người dùng</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary text-lg">
            <i class="fas fa-plus"></i> Thêm mới người dùng
        </a>
    </div>

    <!-- Form tìm kiếm và lọc -->
    <form action="{{ route('admin.users.index') }}" method="GET">
    <div class="filters">
        {{-- Lọc theo Vai trò --}}
        <select name="role" onchange="this.form.submit()">
            <option value="">-- Vai trò --</option>
            <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>Quản trị viên</option>
            <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>Khách hàng</option>
        </select>

        {{-- Lọc theo Trạng thái --}}
        <select name="status" onchange="this.form.submit()">
            <option value="">-- Trạng thái --</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tạm khóa</option>
        </select>

        {{-- Tìm từ khóa --}}
        <div class="d-flex">
            <input type="text" name="keyword" placeholder="Tên, Email, SĐT..." value="{{ request('keyword') }}">

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
            </button>
        </div>

        {{-- Nút Xóa lọc --}}
        {{-- Lưu ý: check request('status') !== null để bắt được cả trường hợp status = 0 --}}
        @if (request('keyword') || request('role') || request('status') !== null)
            <a href="{{ route('admin.users.index') }}" class="btn btn-danger" title="Hủy lọc">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </div>
</form>

    <!-- Hiển thị thông tin lọc hiện tại -->
    @if (request('keyword') || request('role') || request('status') !== null)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-medium text-blue-800">Đang lọc:</span>
                    @if (request('keyword'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Từ khóa: "{{ request('keyword') }}"
                        </span>
                    @endif
                    @if (request('role'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Vai trò: {{ request('role') == '1' ? 'Admin' : 'Khách hàng' }}
                        </span>
                    @endif
                    @if (request('status') !== null)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Trạng thái: {{ request('status') == '1' ? 'Hoạt động' : 'Tạm khóa' }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </a>
            </div>
        </div>
    @endif

    <!-- Bảng người dùng -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th width="80">Ảnh</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Đã xóa?</th>
                        <th width="200">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-center">{{ $user->id }}</td>
                            <td>
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ef4444&color=fff&bold=true"
                                    alt="{{ $user->name }}" class="avatar" />
                            </td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->so_dien_thoai ?? 'N/A' }}</td>
                            <td>
                                @if ($user->id_vai_tro == 1)
                                    <span class="role-badge role-admin">Admin</span>
                                @else
                                    <span class="role-badge role-customer">Khách hàng</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->status == 1)
                                    <span class="status-badge status-active">Hoạt động</span>
                                @else
                                    <span class="status-badge status-active">Tạm khóa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($user->deleted_at)
                                    <span class="text-red-600 font-medium">Có</span>
                                @else
                                    <span class="text-green-600 font-medium">Không</span>
                                @endif
                            </td>
                            <td class="text-center space-x-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info"
                                    title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($user->status == 1)
                                    <a href="{{ route('admin.users.status', $user->id) }}" class="btn btn-sm btn-warning"
                                        title="Khóa">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.users.status', $user->id) }}" class="btn btn-sm btn-success"
                                        title="Mở khóa">
                                        <i class="fas fa-unlock"></i>
                                    </a>
                                @endif
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-delete-item"
                                    data-url="{{ route('admin.users.destroy', $user->id) }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8 text-gray-500">
                                <i class="fas fa-users text-4xl mb-2"></i>
                                <p>Không có người dùng nào.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/asset/js/admin-custom.js') }}"></script>
@endpush
