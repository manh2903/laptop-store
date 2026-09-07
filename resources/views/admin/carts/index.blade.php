@extends('admin.layouts.main') {{-- Nhớ sửa lại đúng đường dẫn layout như đã thảo luận --}}

@section('title', 'Quản lý Giỏ hàng chờ')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Giỏ hàng khách hàng</h1>
            <p class="text-sm text-slate-500">Danh sách các sản phẩm khách đang để trong giỏ nhưng chưa thanh toán.</p>
        </div>
        <div class="flex gap-3">
            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold shadow-sm">
                Tổng cộng: {{ $carts->total() }} giỏ hàng
            </span>
        </div>
    </div>

    {{-- Bảng danh sách --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">Sản phẩm trong giỏ</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider text-center">Số lượng</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider text-center">Cập nhật cuối</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($carts as $cart)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                {{ substr($cart->nguoiDung->ho_ten ?? 'N', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $cart->nguoiDung->ho_ten ?? 'Khách vãng lai' }}</p>
                                <p class="text-xs text-slate-500">{{ $cart->nguoiDung->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            @foreach($cart->chiTiet as $item)
                            <div class="flex items-center gap-2 group">
                                <span class="text-xs font-medium text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">
                                    x{{ $item->so_luong }}
                                </span>
                                <span class="text-sm text-slate-600 truncate max-w-[200px]" title="{{ $item->sanPham->ten_san_pham ?? 'Sản phẩm lỗi' }}">
                                    {{ $item->sanPham->ten_san_pham ?? 'Sản phẩm đã bị xóa' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold leading-none text-blue-600 bg-blue-50 rounded-full border border-blue-100">
                            {{ $cart->chiTiet->sum('so_luong') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-sm text-slate-600">{{ $cart->updated_at->format('H:i d/m/Y') }}</p>
                        <p class="text-[10px] text-slate-400 italic">({{ $cart->updated_at->diffForHumans() }})</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2 text-sm">
                           <button type="button" 
        onclick="viewCart({{ $cart->id }})" 
        class="px-3 py-1.5 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 shadow-sm transition">
    <i class="fas fa-eye mr-1 text-blue-500"></i> Xem
</button>
                            <button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition font-medium">
                                <i class="fas fa-paper-plane mr-1 text-[10px]"></i> Nhắc nhở
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                        <i class="fas fa-shopping-basket text-4xl mb-3 block opacity-20"></i>
                        Hiện chưa có giỏ hàng nào đang chờ.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Phân trang --}}
        @if($carts->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $carts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

{{-- Modal Xem chi tiết --}}
<div id="cartModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Chi tiết giỏ hàng</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="modalContent" class="space-y-4">
                {{-- Dữ liệu sẽ đổ vào đây bằng JS --}}
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 text-right bg-slate-50">
            <button onclick="closeModal()" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition font-medium">Đóng</button>
        </div>
    </div>
</div>

<script>
function viewCart(id) {
    const modal = document.getElementById('cartModal');
    const content = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    content.innerHTML = '<div class="text-center py-10 w-full"><i class="fas fa-spinner fa-spin text-3xl text-blue-500"></i><p>Đang tải...</p></div>';

    fetch(`/admin/carts/${id}`)
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                // Nếu server báo lỗi 500, hiện lỗi đó ra modal luôn
                throw new Error(data.message || 'Lỗi không xác định');
            }
            return data;
        })
        .then(data => {
            renderModalData(data); // Hàm vẽ bảng dữ liệu
        })
        .catch(error => {
            content.innerHTML = `
                <div class="text-red-500 p-4 bg-red-50 rounded-lg border border-red-100">
                    <p class="font-bold">CÓ LỖI XẢY RA:</p>
                    <p class="text-sm">${error.message}</p>
                </div>`;
            console.error('Chi tiết:', error);
        });
}
function closeModal() {
    const modal = document.getElementById('cartModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>