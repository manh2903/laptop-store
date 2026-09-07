@extends('admin.layouts.main')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6">
    
    {{-- 1. HEADER & THỐNG KÊ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Danh Sách Sản Phẩm</h1>
            <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-600">
                <span class="flex items-center bg-white px-2.5 py-1.5 rounded-lg border border-gray-200 shadow-sm">
                    <i class="fas fa-box mr-2 text-indigo-500"></i> Tổng: <b class="ml-1 text-gray-900">{{ $products->total() }}</b>
                </span>
                <span class="flex items-center bg-white px-2.5 py-1.5 rounded-lg border border-gray-200 shadow-sm">
                    <i class="fas fa-eye-slash mr-2 text-gray-400"></i> Đang ẩn: <b class="ml-1 text-gray-900">{{ \App\Models\SanPham::where('trang_thai', 0)->count() }}</b>
                </span>
                <span class="flex items-center bg-white px-2.5 py-1.5 rounded-lg border border-red-200 shadow-sm">
                    <i class="fas fa-times-circle mr-2 text-red-500"></i> Hết hàng: <b class="ml-1 text-red-600">{{ \App\Models\SanPham::where('so_luong_ton', 0)->count() }}</b>
                </span>
                <span class="flex items-center bg-white px-2.5 py-1.5 rounded-lg border border-yellow-200 shadow-sm">
                    <i class="fas fa-exclamation-circle mr-2 text-yellow-500"></i> Sắp hết: <b class="ml-1 text-yellow-600">{{ \App\Models\SanPham::where('so_luong_ton', '>', 0)->where('so_luong_ton', '<=', 5)->count() }}</b>
                </span>
                <span class="flex items-center bg-white px-2.5 py-1.5 rounded-lg border border-blue-200 shadow-sm">
                    <i class="fas fa-history mr-2 text-blue-500"></i> Cập nhật lần cuối: 
                    <b id="last-page-update" class="ml-1 text-blue-600">
                        {{-- Hiển thị thời gian cập nhật mới nhất trong danh sách hiện tại --}}
                        {{ $products->count() > 0 && $products->max('ngay_cap_nhat') ? \Carbon\Carbon::parse($products->max('ngay_cap_nhat'))->format('H:i d/m/Y') : 'Chưa có dữ liệu' }}
                    </b>
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl shadow-sm hover:bg-indigo-700 transition-all duration-200 flex items-center text-sm font-semibold">
                <i class="fas fa-plus mr-2 text-xs"></i> Thêm Mới
            </a>
        </div>
    </div>

    {{-- 2. BỘ LỌC TÌM KIẾM --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-4 relative">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm tên, mã SKU..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>
            <div class="md:col-span-2">
                <select name="category_id" class="w-full px-3 py-3 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 cursor-pointer bg-white">
                    <option value="">-- Danh mục --</option>
                    @foreach($categories as $cate)
                        <option value="{{ $cate->id }}" {{ request('category_id') == $cate->id ? 'selected' : '' }}>{{ $cate->ten_danh_muc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="brand_id" class="w-full px-3 py-3 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 cursor-pointer bg-white">
                    <option value="">-- Thương hiệu --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->ten_thuong_hieu }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="status" class="w-full px-3 py-3 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 cursor-pointer bg-white">
                    <option value="">-- Trạng thái --</option>
                    <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Đang hiện</option>
                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Đang ẩn</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="stock_status" class="w-full px-3 py-3 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 cursor-pointer bg-white">
                    <option value="">-- Tồn kho --</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Sắp hết (<=5)</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn hàng (>5)</option>
                </select>
            </div>
            <div class="md:col-span-12 flex justify-end gap-3 border-t border-gray-100 pt-4 mt-2">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-bold transition-all flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i> Đặt lại
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center shadow-sm hover:shadow-md">
                    <i class="fas fa-filter mr-2"></i> Áp dụng
                </button>
            </div>
        </form>
    </div>

    {{-- 3. BẢNG DỮ LIỆU --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold">
                        <th class="px-4 py-4 text-center w-16">ID</th>
                        <th class="px-4 py-4 text-center w-20">Ảnh</th>
                        <th class="px-4 py-4">Thông tin sản phẩm</th>
                        <th class="px-4 py-4 text-right">Giá bán</th>
                        <th class="px-4 py-4 text-center w-32">Kho</th>
                        <th class="px-4 py-4 text-center w-28">Trạng thái</th>
                        <th class="px-4 py-4 text-right w-24">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors group {{ $product->trang_thai == 0 ? 'bg-gray-50 opacity-75' : '' }}">
                        {{-- ID --}}
                        <td class="px-4 py-4 text-center text-gray-400 font-mono">#{{ $product->id }}</td>
                        
                        {{-- ẢNH (Tag SALE nhỏ gọn) --}}
                        <td class="px-4 py-4 text-center">
                            <div class="relative h-12 w-12 inline-block">
                                <img src="{{ asset($product->anh_dai_dien) }}" class="h-full w-full object-cover rounded-lg border border-gray-100 shadow-sm" alt="img">
                                @if($product->is_flash_sale)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-bl-lg shadow-sm leading-none z-10 border border-white">SALE</span>
                                @endif
                            </div>
                        </td>

                        {{-- THÔNG TIN --}}
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1.5">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="font-bold text-gray-800 hover:text-indigo-600 line-clamp-1 transition-colors text-[15px] {{ $product->trang_thai == 0 ? 'line-through' : '' }}" title="{{ $product->ten_san_pham }}">
                                    {{ $product->ten_san_pham }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[11px] font-mono border border-gray-200 flex items-center" title="Mã SKU">
                                        <i class="fas fa-barcode mr-1.5 text-gray-400"></i>{{ $product->ma_sku ?? 'NO-SKU' }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-xs text-blue-600 font-semibold">{{ $product->danhMuc->ten_danh_muc ?? '-' }}</span>
                                    @if($product->thuongHieu)
                                        <span class="text-gray-300">|</span>
                                        <span class="text-xs text-indigo-600 font-semibold">{{ $product->thuongHieu->ten_thuong_hieu }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- GIÁ BÁN --}}
                        <td class="px-4 py-4 text-right">
                            <div class="flex flex-col items-end">
                                @if($product->gia_khuyen_mai > 0 && $product->gia_khuyen_mai < $product->gia_ban)
                                    <span class="font-extrabold text-indigo-600 text-base">{{ number_format($product->gia_khuyen_mai, 0, ',', '.') }}đ</span>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-xs text-gray-400 line-through font-medium">{{ number_format($product->gia_ban, 0, ',', '.') }}đ</span>
                                        <span class="text-[9px] bg-red-100 text-red-700 px-1.5 py-0.5 rounded-md font-bold">
                                            -{{ round((($product->gia_ban - $product->gia_khuyen_mai)/$product->gia_ban)*100) }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="font-extrabold text-gray-700 text-base">{{ number_format($product->gia_ban, 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                        </td>

                        {{-- KHO HÀNG --}}
                        <td class="px-4 py-4 text-center">
                            @if($product->so_luong_ton <= 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                                    HẾT HÀNG
                                </span>
                            @elseif($product->so_luong_ton <= 5)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    SẮP HẾT ({{ $product->so_luong_ton }})
                                </span>
                            @else
                                <span class="font-bold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">{{ $product->so_luong_ton }}</span>
                            @endif
                        </td>

                        {{-- TRẠNG THÁI (ĐÃ SỬA CSS NÚT TOGGLE CHUẨN) --}}
                        <td class="px-4 py-4 text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer change-status" data-id="{{ $product->id }}" {{ $product->trang_thai ? 'checked' : '' }}>
                                {{-- Thanh trượt --}}
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500 shadow-inner"></div>
                            </label>
                        </td>

                        {{-- THAO TÁC --}}
                        <td class="px-4 py-4 text-right">
    <div class="flex justify-end gap-2">
        
       {{-- Nút Xem chi tiết (Trong Admin) --}}
<a href="{{ route('admin.products.show', $product->id) }}" 
   class="h-9 w-9 flex items-center justify-center bg-white hover:bg-teal-50 text-gray-500 hover:text-teal-600 rounded-xl transition-all border border-gray-200 hover:border-teal-200 shadow-sm" 
   title="Xem chi tiết (Admin)">
    <i class="fas fa-eye text-xs"></i>
</a>

        {{-- 2. NÚT SỬA (CŨ) --}}
        <a href="{{ route('admin.products.edit', $product->id) }}" class="h-9 w-9 flex items-center justify-center bg-white hover:bg-indigo-50 text-gray-500 hover:text-indigo-600 rounded-xl transition-all border border-gray-200 hover:border-indigo-200 shadow-sm" title="Sửa">
            <i class="fas fa-pen text-xs"></i>
        </a>
        
        {{-- 3. NÚT XÓA (CŨ) --}}
        <button type="button" 
            class="h-9 w-9 flex items-center justify-center bg-white hover:bg-red-50 text-gray-500 hover:text-red-600 rounded-xl transition-all border border-gray-200 hover:border-red-200 shadow-sm" 
            onclick="confirmDelete('{{ $product->id }}')" 
            title="Xóa">
            <i class="fas fa-trash text-xs"></i>
        </button>
                                
                                <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-search text-3xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-1">Không tìm thấy kết quả</h3>
                                <a href="{{ route('admin.products.index') }}" class="text-indigo-500 text-sm mt-1 hover:underline font-medium">Xóa bộ lọc & Xem tất cả</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PHÂN TRANG --}}
       {{-- PHÂN TRANG (CẬP NHẬT GIAO DIỆN MỚI) --}}
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Text thống kê: Mảnh hơn (font-light), nghiêng (italic), màu dịu (text-gray-500) --}}
            <div class="text-xs text-gray-500 font-light">
                Hiển thị từ <span class="font-medium text-gray-700 ">{{ $products->firstItem() }}</span> 
                đến <span class="font-medium text-gray-700 ">{{ $products->lastItem() }}</span> 
                trong tổng số <span class="font-medium text-gray-700 ">{{ $products->total() }}</span> sản phẩm
            </div>

            {{-- Gọi View phân trang riêng (admin.partials.pagination) --}}
            <div>
                {{ $products->appends(request()->all())->links('admin.partials.pagination') }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- INCLUDE PARTIALS --}}
@include('admin.partials.notification') 
@include('admin.partials.delete_modal')

{{-- SCRIPTS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    // 1. AJAX đổi trạng thái
    $('.change-status').on('change', function() {
        let prodId = $(this).data('id');
        let isChecked = $(this).is(':checked') ? 1 : 0;
        let _this = $(this);
        let row = _this.closest('tr');
        let nameLink = row.find('td:nth-child(3) a');

        $.ajax({
            url: "{{ route('admin.products.update-status') }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', id: prodId, trang_thai: isChecked },
            success: function(res) {
                if(res.success) {
                    $('#last-page-update').text(res.new_date); 
                    
                    // --- GỌI HÀM SHOW TOAST TỰ CHẾ (SỬ DỤNG TEMPLATE CÓ SẴN CỦA BẠN) ---
                    // Hàm showAppToast() được định nghĩa bên dưới
                    showAppToast(isChecked ? 'Đã hiển thị sản phẩm!' : 'Đã ẩn sản phẩm!', 'success');

                    // Xử lý giao diện
                    if(isChecked) {
                        row.removeClass('bg-gray-50 opacity-75');
                        nameLink.removeClass('line-through');
                    } else {
                        row.addClass('bg-gray-50 opacity-75');
                        nameLink.addClass('line-through');
                    }
                }
            },
            error: function() {
                showAppToast('Lỗi kết nối máy chủ!', 'error');
                _this.prop('checked', !isChecked);
            }
        });
    });

    // 2. Tự động hiển thị Toast nếu có Session Flash (từ redirect controller)
    @if(session('success'))
        // Mặc định template partial của bạn đã tự handle session('success')
        // Nhưng nếu muốn chắc chắn gọi qua JS:
        // showAppToast("{{ session('success') }}", 'success');
    @endif
});

// --- HÀM TẠO TOAST TỪ TEMPLATE (ĐỂ DÙNG CHO AJAX) ---
function showAppToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    
    // Tìm template phù hợp trong file notification.blade.php
    // Lưu ý: File partial của bạn phải có thẻ <template id="toast-success-template">
    // Nếu không có thẻ template, đoạn code này sẽ không chạy được. 
    // Trong trường hợp file partial chỉ render thẳng HTML từ session, ta cần tự tạo HTML cho Ajax.
    
    let template = document.getElementById('toast-success-template');
    
    if(!container) {
        console.warn('Không tìm thấy container #toast-container. Kiểm tra lại partial notification.');
        return;
    }

    if (!template) {
        // FALLBACK: Nếu không có thẻ <template>, ta tự tạo HTML giống hệt style của bạn
        const toastDiv = document.createElement('div');
        toastDiv.className = "toast-modern pointer-events-auto relative w-96 bg-white/95 border border-emerald-100 shadow-2xl rounded-2xl overflow-hidden flex items-start p-4 group hover:shadow-emerald-100/50 transition-all duration-300";
        
        // Màu sắc dựa trên type
        const isError = type === 'error';
        const iconColor = isError ? 'text-red-600' : 'text-emerald-600';
        const iconBg = isError ? 'bg-red-100' : 'bg-emerald-100';
        const iconClass = isError ? 'fa-exclamation' : 'fa-check';
        const title = isError ? 'Lỗi!' : 'Thành công!';
        const borderClass = isError ? 'border-red-100' : 'border-emerald-100';
        const progressClass = isError ? 'from-red-400 to-red-600' : 'from-emerald-400 to-emerald-600';

        toastDiv.classList.replace('border-emerald-100', borderClass); // Cập nhật border nếu lỗi

        toastDiv.innerHTML = `
            <div class="flex-shrink-0 mr-4">
                <div class="w-10 h-10 ${iconBg} rounded-full flex items-center justify-center ${iconColor} shadow-inner">
                    <i class="fas ${iconClass} text-lg"></i>
                </div>
            </div>
            <div class="flex-1 pr-6">
                <h4 class="font-bold text-gray-800 text-sm font-sans mb-1">${title}</h4>
                <p class="text-gray-500 text-xs leading-relaxed">${message}</p>
            </div>
            <button onclick="closeToast(this)" class="absolute top-3 right-3 text-gray-300 hover:${iconColor} transition-all duration-300 p-1">
                <i class="fas fa-times"></i>
            </button>
            <div class="toast-progress-bar bg-gradient-to-r ${progressClass}" style="animation: progressRun 4s linear forwards;"></div>
        `;
        
        container.appendChild(toastDiv);
        
        // Tự động tắt sau 4s
        setTimeout(() => { if(typeof closeToast === "function") closeToast(toastDiv); else toastDiv.remove(); }, 4000);
        return;
    }

    // NẾU CÓ TEMPLATE (Dùng cách clone chuẩn)
    const clone = template.content.cloneNode(true);
    clone.querySelector('.toast-msg-text').textContent = message;
    container.appendChild(clone);
    const newToast = container.lastElementChild;
    setTimeout(() => { if (typeof closeToast === "function") closeToast(newToast); else newToast.remove(); }, 4000);
}

// --- HÀM GỌI MODAL XÓA (KẾT NỐI PARTIAL) ---
function confirmDelete(id) {
    let form = document.getElementById('delete-form-' + id);
    if (form) {
        const event = new Event('submit', { cancelable: true });
        form.dispatchEvent(event); 
        // Nếu form.dispatchEvent không hiện modal, hãy check lại file partial có đúng ID form không
    }
}
</script>
@endsection