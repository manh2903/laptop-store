@extends('admin.layouts.main')
@section('title', 'Quản lý Thương hiệu')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Quản lý Thương hiệu</h1>
        <a href="{{ route('admin.brands.create') }}" class="bg-indigo-600 text-white px-4 py-2.5 rounded-lg shadow-lg hover:bg-indigo-700 transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Thêm Hãng Mới
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500">
                <tr class="text-center">
                    <th class="px-6 py-4 w-20">ID</th>
                    <th class="px-6 py-4 w-32">Logo</th>
                    <th class="px-6 py-4 text-left">Tên Hãng</th>
                    <th class="px-6 py-4 w-24">Thứ tự</th> {{-- Cột thứ tự mới --}}
                    <th class="px-6 py-4">Trạng thái</th>
                    <th class="px-6 py-4">Cập nhật cuối</th>
                    <th class="px-6 py-4 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @foreach($brands as $brand)
                <tr class="hover:bg-gray-50/50 transition duration-150 group">
                    <td class="px-6 py-4 text-center font-mono font-bold text-gray-400">#{{ $brand->id }}</td>
                    
                    <td class="px-6 py-4 flex justify-center">
                        <div class="h-12 w-12 rounded-full border border-gray-200 bg-white p-1 flex items-center justify-center shadow-sm overflow-hidden">
                            <img src="{{ asset($brand->hinh_anh) }}" class="h-full w-full object-contain rounded-full">
                        </div>
                    </td>

                    {{-- Tên hãng: Xám, mảnh, in nghiêng --}}
                    <td class="px-6 py-4">
                        <span class="text-gray-400 font-light italic">{{ $brand->ten_thuong_hieu }}</span>
                    </td>

                    {{-- Cột thứ tự --}}
                    <td class="px-6 py-4 text-center">
                        <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs font-mono">
                            {{ $brand->thu_tu_sap_xep ?? 0 }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer change-status" data-id="{{ $brand->id }}" {{ $brand->trang_thai ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div id="update-time-{{ $brand->id }}" class="text-xs text-gray-600 font-medium">
                            @if($brand->ngay_cap_nhat)
                                <span class="block text-indigo-600 font-bold"><i class="far fa-clock mr-1"></i>{{ $brand->ngay_cap_nhat->format('H:i:s') }}</span>
                                <span class="block text-[10px] text-gray-400 mt-0.5">{{ $brand->ngay_cap_nhat->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg bg-white border border-gray-100 shadow-sm"><i class="fas fa-pen text-sm"></i></a>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg bg-white border border-gray-100 shadow-sm"><i class="fas fa-trash text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Giữ nguyên phần Script Ajax cũ của bạn --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.change-status').on('change', function() {
        let brandId = $(this).data('id');
        let isChecked = $(this).is(':checked') ? 1 : 0;
        let _this = $(this);

        $.ajax({
            url: "{{ route('admin.brands.update-status') }}",
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', id: brandId, trang_thai: isChecked },
            success: function(res) {
                if(res.success) {
                    let parts = res.new_date.split(' ');
                    $('#update-time-' + brandId).html(`<span class="block text-indigo-600 font-bold"><i class="far fa-clock mr-1"></i>${parts[0]}</span><span class="block text-[10px] text-gray-400 mt-0.5">${parts[1]}</span>`);
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
        const oldToasts = container.querySelectorAll('.toast-modern');
        oldToasts.forEach(t => t.remove());
        const clone = template.content.cloneNode(true);
        clone.querySelector('.toast-msg-text').textContent = msg;
        container.appendChild(clone);
        const newToast = container.lastElementChild;
        setTimeout(() => { if (typeof closeToast === "function") closeToast(newToast); else newToast.remove(); }, 4000);
    }
});
</script>
@endsection