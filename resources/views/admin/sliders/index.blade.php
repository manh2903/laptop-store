@extends('admin.layouts.main')
@section('title', 'Quản lý Slider & Banner')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Danh sách Slider/Banner</h2>
        {{-- ĐÃ SỬA: admin.sliders.create -> admin.slider.create --}}
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary text-lg">
            <i class="fas fa-plus"></i> Thêm mới Slider
        </a>
    </div>

    {{-- ĐÃ SỬA: admin.sliders.index -> admin.slider.index --}}
    <form action="{{ route('admin.sliders.index') }}" method="GET">
        <div class="filters">
            {{-- Lọc theo Vị trí --}}
            <select name="position" onchange="this.form.submit()">
                <option value="">-- Vị trí hiển thị --</option>
                <option value="main" {{ request('position') == 'main' ? 'selected' : '' }}>Slider chính (To)</option>
                <option value="right_top" {{ request('position') == 'right_top' ? 'selected' : '' }}>Banner phải (Trên)
                </option>
                <option value="right_bottom" {{ request('position') == 'right_bottom' ? 'selected' : '' }}>Banner phải
                    (Dưới)</option>
            </select>

            {{-- Lọc theo Trạng thái --}}
            <select name="status" onchange="this.form.submit()">
                <option value="">-- Trạng thái --</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hiện</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đang ẩn</option>
            </select>

            {{-- Tìm từ khóa --}}
            <div class="d-flex">
                <input type="text" name="keyword" placeholder="Tên banner..." value="{{ request('keyword') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            {{-- Nút Xóa lọc --}}
            @if (request('keyword') || request('position') || request('status') !== null)
                {{-- ĐÃ SỬA: admin.sliders.index -> admin.slider.index --}}
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-danger" title="Hủy lọc">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>

    {{-- Phần hiển thị thông tin đang lọc giữ nguyên logic --}}
    @if (request('keyword') || request('position') || request('status') !== null)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-medium text-blue-800">Đang lọc:</span>
                    @if (request('keyword'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Từ khóa: "{{ request('keyword') }}"
                        </span>
                    @endif
                    @if (request('position'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Vị trí:
                            @if (request('position') == 'main')
                                Slider chính
                            @elseif(request('position') == 'right_top')
                                Banner phải (Trên)
                            @else
                                Banner phải (Dưới)
                            @endif
                        </span>
                    @endif
                    @if (request('status') !== null)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                            Trạng thái: {{ request('status') == '1' ? 'Đang hiện' : 'Đang ẩn' }}
                        </span>
                    @endif
                </div>
                {{-- ĐÃ SỬA --}}
                <a href="{{ route('admin.sliders.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </a>
            </div>
        </div>
    @endif


    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="150">Hình ảnh</th>
                    <th>Tên & Link</th>
                    <th>Vị trí</th>
                    <th>Trang hiển thị</th>
                    <th width="80" class="text-center">Thứ tự</th>
                    <th width="100">Trạng thái</th>
                    <th width="150">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sliders as $slider)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ asset('uploads/slider/' . $slider->image) }}" alt="{{ $slider->name }}"
                                class="rounded border object-cover" style="width: 120px; height: 70px;">
                        </td>
                        <td>
                            <div class="font-bold text-gray-800">{{ $slider->name }}</div>
                            @if ($slider->link)
                                <div class="text-xs text-blue-500 truncate max-w-xs">
                                    <i class="fas fa-link"></i> {{ $slider->link }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($slider->position == 'main')
                                <span class="badge badge-primary">Slider Chính</span>
                            @elseif($slider->position == 'right_top')
                                <span class="badge badge-info">Phải (Trên)</span>
                            @else
                                <span class="badge badge-warning">Phải (Dưới)</span>
                            @endif
                        </td>
                        <td>
                            @if ($slider->category_id)
                                <span class="text-indigo-600 font-medium">
                                    <i class="fas fa-laptop"></i> {{ $slider->category->name ?? 'Danh mục lỗi' }}
                                </span>
                            @else
                                <span class="text-green-600 font-bold">
                                    <i class="fas fa-home"></i> Trang chủ
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="font-mono font-bold">{{ $slider->sort_order }}</span>
                        </td>
                        <td>
                            @if ($slider->status == 1)
                                <span
                                    class="status-badge status-active text-green-600 bg-green-100 px-2 py-1 rounded">Hiện</span>
                            @else
                                <span
                                    class="status-badge status-inactive text-red-600 bg-red-100 px-2 py-1 rounded">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-center space-x-1">
                            {{-- ĐÃ SỬA: admin.sliders.edit -> admin.slider.edit --}}
                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-info"
                                title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-delete-item"
                                data-url="{{ route('admin.sliders.destroy', $slider->id) }}" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            <i class="far fa-images text-4xl mb-2"></i>
                            <p>Chưa có slider/banner nào.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sliders->links() }}
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/asset/js/admin-custom.js') }}"></script>
@endpush
