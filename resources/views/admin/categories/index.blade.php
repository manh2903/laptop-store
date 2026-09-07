@extends('admin.layouts.main')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6">
    
    {{-- PHẦN 1: HEADER --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Danh Mục Sản Phẩm</h1>
                {{-- DÒNG CẬP NHẬT LẦN CUỐI ĐẦU TRANG --}}
                <p class="text-xs text-gray-500 mt-1 flex items-center">
                    <i class="fas fa-history mr-1.5 text-indigo-500"></i>
                    Hệ thống cập nhật lần cuối: 
                    <span id="last-page-update" class="font-bold text-indigo-600 ml-1">
                        {{ $categories->max('ngay_cap_nhat') ? $categories->max('ngay_cap_nhat')->format('H:i:s d/m/Y') : 'Chưa có dữ liệu' }}
                    </span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.categories.create') }}?type={{ $type != 'all' ? $type : 'menu' }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-md flex items-center">
                    <i class="fas fa-plus mr-2"></i> Thêm mới
                </a>
            </div>
        </div>

        {{-- Cards Thống kê (4 Cột) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Tổng cộng</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="h-10 w-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600"><i class="fas fa-layer-group"></i></div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Mega Menu</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['menu'] }}</p>
                </div>
                <div class="h-10 w-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600"><i class="fas fa-bars"></i></div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Nhu cầu</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['need'] }}</p>
                </div>
                <div class="h-10 w-10 bg-teal-50 rounded-full flex items-center justify-center text-teal-600"><i class="fas fa-filter"></i></div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between opacity-75">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Đang ẩn</p>
                    {{-- ID counter-hidden ĐỂ CẬP NHẬT REALTIME --}}
                    <p id="counter-hidden" class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['hidden'] }}</p>
                </div>
                <div class="h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500"><i class="fas fa-eye-slash"></i></div>
            </div>
        </div>
    </div>

    {{-- PHẦN 2: BẢNG DỮ LIỆU & TABS --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        
        <div class="border-b border-gray-200 flex overflow-x-auto">
            <a href="{{ route('admin.categories.index', ['type' => 'all']) }}" 
               class="px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $type == 'all' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
               <i class="fas fa-list mr-2"></i> Tất cả
            </a>
            <a href="{{ route('admin.categories.index', ['type' => 'menu']) }}" 
               class="px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $type == 'menu' ? 'border-blue-600 text-blue-600 bg-blue-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
               <i class="fas fa-sitemap mr-2"></i> Mega Menu
            </a>
            <a href="{{ route('admin.categories.index', ['type' => 'need']) }}" 
               class="px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 {{ $type == 'need' ? 'border-teal-600 text-teal-600 bg-teal-50/50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
               <i class="fas fa-filter mr-2"></i> Nhu cầu
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr class="text-xs uppercase font-semibold text-gray-500 tracking-wider">
                        <th class="px-6 py-4 w-16">ID</th>
                        <th class="px-6 py-4">Tên danh mục (Cấu trúc cây)</th>
                        <th class="px-6 py-4 text-center">Phân loại</th>
                        <th class="px-6 py-4 text-center">Vị trí</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition duration-150 group">
                        <td class="px-6 py-4 text-gray-400 text-xs font-mono">
                            #{{ str_pad($cat->id, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded bg-white border border-gray-200 flex items-center justify-center mr-3 overflow-hidden">
                                    @if($cat->hinh_anh)
                                        <img src="{{ asset('storage/' . $cat->hinh_anh) }}" class="h-full w-full object-contain p-1">
                                    @else
                                        <span class="text-[10px] text-gray-300">N/A</span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                                        @if($cat->parent_id != 0)
                                            <span class="text-gray-300 mr-2">└──</span>
                                        @endif
                                        <span>{{ $cat->ten_danh_muc }}</span>
                                    </h3>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $badgeStyle = match($cat->loai) {
                                    'menu' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'need' => 'bg-teal-50 text-teal-700 border-teal-100',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $badgeStyle }}">
                                {{ $cat->loai }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="font-mono text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                {{ $cat->thu_tu }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer change-status" 
                                       data-id="{{ $cat->id }}" 
                                       {{ $cat->trang_thai ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="p-1.5 bg-white border border-gray-100 rounded text-indigo-600 hover:bg-indigo-50 shadow-sm transition">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                {{-- Nút xóa dùng Form chuẩn để Modal tự bắt --}}
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-white border border-gray-100 rounded text-red-600 hover:bg-red-50 shadow-sm transition">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">Chưa có danh mục nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $categories->appends(['type' => $type])->links() }}
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.change-status').on('change', function() {
        let catId = $(this).data('id');
        let isChecked = $(this).is(':checked') ? 1 : 0;
        let _this = $(this);

        $.ajax({
            url: "{{ route('admin.categories.update-status') }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', id: catId, trang_thai: isChecked },
            success: function(res) {
                if(res.success) {
                    // 1. CẬP NHẬT DÒNG THỜI GIAN ĐẦU TRANG
                    $('#last-page-update').text(res.new_date);
                    
                    // 2. CẬP NHẬT SỐ LƯỢNG ĐANG ẨN REALTIME
                    $('#counter-hidden').text(res.hidden_count);

                    // 3. HIỆN THÔNG BÁO NẢY (TOAST)
                    showAjaxToast(res.message);
                }
            },
            error: function() {
                alert('Lỗi kết nối máy chủ!');
                _this.prop('checked', !isChecked);
            }
        });
    });

    function showAjaxToast(msg) {
        const container = document.getElementById('toast-container');
        const template = document.getElementById('toast-success-template');
        if(!container || !template) return;
        container.querySelectorAll('.toast-modern').forEach(t => t.remove());
        const clone = template.content.cloneNode(true);
        clone.querySelector('.toast-msg-text').textContent = msg;
        container.appendChild(clone);
        const newToast = container.lastElementChild;
        setTimeout(() => { if (typeof closeToast === "function") closeToast(newToast); else newToast.remove(); }, 4000);
    }
});
</script>
@endsection