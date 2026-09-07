@extends('admin.layouts.main')

@section('title', 'Chi tiết: ' . $product->ten_san_pham)

@section('content')
<div class="p-6 bg-gray-50/50 min-h-screen">
    
    {{-- ==================================================================================== --}}
    {{-- 1. HEADER: TIÊU ĐỀ & THÔNG TIN CẬP NHẬT --}}
    {{-- ==================================================================================== --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 flex items-center gap-2 tracking-tight">
                Chi tiết sản phẩm
            </h1>
            <div class="text-sm text-gray-500 mt-2 flex flex-wrap items-center gap-3">
                <span class="bg-white px-3 py-1 rounded border border-gray-200 text-gray-600 font-mono font-bold shadow-sm">
                    ID: #{{ $product->id }}
                </span>
                <span class="bg-white px-3 py-1 rounded border border-gray-200 text-gray-600 font-mono font-bold shadow-sm">
                    SKU: {{ $product->ma_sku ?? 'N/A' }}
                </span>
                
                {{-- HIỂN THỊ NGÀY CẬP NHẬT --}}
                <span class="flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded border border-blue-100 font-medium shadow-sm">
                    <i class="far fa-clock"></i> 
                    Cập nhật lần cuối: 
                    <span class="font-bold ml-1">
                        {{ $product->ngay_cap_nhat ? \Carbon\Carbon::parse($product->ngay_cap_nhat)->format('H:i - d/m/Y') : 'Chưa cập nhật' }}
                    </span>
                </span>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 font-semibold text-sm transition shadow-sm">
                <i class="fas fa-arrow-left mr-1.5"></i> Quay lại
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold text-sm transition shadow-lg hover:shadow-indigo-500/30">
                <i class="fas fa-pen mr-1.5"></i> Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        {{-- ==================================================================================== --}}
        {{-- CỘT TRÁI (1/3): ẢNH ĐẠI DIỆN, GIÁ, THÔNG TIN CHUNG, MEDIA --}}
        {{-- ==================================================================================== --}}
        <div class="xl:col-span-1 space-y-8">
            
            {{-- CARD 1: TỔNG QUAN & ẢNH ĐẠI DIỆN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-200 text-center relative">
                    <div class="w-full h-64 bg-white rounded-xl border border-gray-200 flex items-center justify-center p-4">
                        <img src="{{ asset($product->anh_dai_dien) }}" class="max-w-full max-h-full object-contain" alt="Ảnh đại diện">
                    </div>
                    
                    {{-- Badges Trạng thái --}}
                    <div class="absolute top-8 left-8 flex flex-col gap-2">
                        @if($product->is_flash_sale)
                            <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded shadow-sm border border-yellow-500/20 animate-pulse">⚡ FLASH SALE</span>
                        @endif
                        @if($product->tra_gop_0_phan_tram)
                            <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm border border-blue-600/20">TRẢ GÓP 0%</span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6 space-y-5">
                    <h2 class="text-xl font-bold text-gray-900 leading-snug">{{ $product->ten_san_pham }}</h2>
                    
                    {{-- Giá bán & Khuyến mãi --}}
                    <div class="flex justify-between items-end border-b border-dashed border-gray-200 pb-5">
                        <span class="text-gray-500 font-medium text-sm">Giá hiển thị:</span>
                        <div class="text-right">
                            @if($product->gia_khuyen_mai > 0)
                                <div class="flex items-center justify-end gap-2">
                                    <span class="block text-2xl font-extrabold text-red-600">{{ number_format($product->gia_khuyen_mai) }} ₫</span>
                                    @php
                                        $percent = ($product->gia_ban > 0) ? round((($product->gia_ban - $product->gia_khuyen_mai) / $product->gia_ban) * 100) : 0;
                                    @endphp
                                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-1.5 py-0.5 rounded">-{{ $percent }}%</span>
                                </div>
                                <span class="text-sm text-gray-400 line-through font-medium">{{ number_format($product->gia_ban) }} ₫</span>
                            @else
                                <span class="text-2xl font-extrabold text-gray-800">{{ number_format($product->gia_ban) }} ₫</span>
                            @endif
                        </div>
                    </div>

                    {{-- Thông tin chi tiết cơ bản --}}
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Danh mục:</span>
                            <span class="font-bold text-gray-800">{{ $product->danhMuc->ten_danh_muc ?? '---' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Thương hiệu:</span>
                            <span class="font-bold text-indigo-600">{{ $product->thuongHieu->ten_thuong_hieu ?? '---' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tồn kho:</span>
                            <span class="font-bold {{ $product->so_luong_ton > 0 ? 'text-gray-800' : 'text-red-500' }}">
                                {{ $product->so_luong_ton }} chiếc
                            </span>
                        </div>
                        <div class="flex justify-between text-sm items-center">
                            <span class="text-gray-500">Trạng thái:</span>
                            @if($product->trang_thai)
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200 flex items-center gap-1"><i class="fas fa-check-circle"></i> Đang hiện</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-bold border border-gray-200 flex items-center gap-1"><i class="fas fa-eye-slash"></i> Đang ẩn</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD 2: THƯ VIỆN MEDIA (VIDEO & GALLERY) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-5 flex items-center border-b pb-3">
                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-images"></i></span>
                    Thư viện Media
                </h3>

                {{-- Video --}}
                @if($product->video_url)
                    <div class="mb-5">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2"><i class="fas fa-video mr-1"></i> Video giới thiệu</p>
                        <div class="aspect-w-16 aspect-h-9 bg-black rounded-lg overflow-hidden border border-gray-200">
                            <video controls class="w-full h-full object-cover">
                                <source src="{{ asset($product->video_url) }}">
                            </video>
                        </div>
                    </div>
                @endif

                {{-- Gallery Images --}}
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2"><i class="fas fa-layer-group mr-1"></i> Album ảnh chi tiết</p>
                    @if($product->hinhAnh && $product->hinhAnh->where('loai', 'gallery')->count() > 0)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->hinhAnh->where('loai', 'gallery') as $img)
                                <a href="{{ asset($img->duong_dan_anh) }}" target="_blank" class="aspect-square rounded-lg border border-gray-200 overflow-hidden relative group bg-gray-50">
                                    <img src="{{ asset($img->duong_dan_anh) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Chưa có ảnh gallery.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- ==================================================================================== --}}
        {{-- CỘT PHẢI (2/3): TÍNH NĂNG NỔI BẬT, MÔ TẢ, CẤU HÌNH, THÔNG SỐ --}}
        {{-- ==================================================================================== --}}
        <div class="xl:col-span-2 space-y-8">
            
            {{-- CARD 3: TÍNH NĂNG NỔI BẬT (TÁCH RIÊNG NHƯ YÊU CẦU) --}}
            @if($product->tinh_nang_noi_bat)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                    <span class="bg-yellow-100 text-yellow-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-star"></i></span>
                    Tính năng nổi bật
                </h3>
                {{-- Style giống ảnh bạn gửi --}}
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="whitespace-pre-line text-gray-800 text-sm leading-relaxed font-medium">
                        {{ $product->tinh_nang_noi_bat }}
                    </div>
                </div>
            </div>
            @endif

            {{-- CARD 4: BIẾN THỂ & CẤU HÌNH --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-800 mb-5 flex items-center border-b pb-3">
                    <span class="bg-purple-100 text-purple-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-cubes"></i></span>
                    Phiên bản & Cấu hình tùy chọn
                </h3>
                
                {{-- Biến thể màu --}}
                <div class="mb-6">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-wider">Màu sắc</p>
                    @if($product->bienThe && $product->bienThe->count() > 0)
                        <div class="space-y-3">
                            @foreach($product->bienThe as $variant)
                                <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50">
                                    <div class="w-12 h-12 rounded-lg border bg-white p-0.5 flex-shrink-0">
                                        <img src="{{ asset($variant->anh_mau) }}" class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-800">{{ $variant->ten_mau }}</p>
                                        <p class="text-xs text-green-600 font-bold">{{ number_format($variant->gia_ban) }} ₫</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Không có biến thể màu.</p>
                    @endif
                </div>

                {{-- Cấu hình nâng cấp --}}
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3 tracking-wider">Cấu hình nâng cấp</p>
                    @if($product->cauHinh && $product->cauHinh->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->cauHinh as $config)
                                <div class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs bg-white shadow-sm flex items-center gap-2">
                                    <span class="font-bold text-gray-700">{{ $config->ten_cau_hinh }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="font-bold text-green-600">+{{ number_format($config->gia_them) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic">Không có cấu hình nâng cấp.</p>
                    @endif
                </div>
            </div>

            {{-- CARD 5: THÔNG SỐ KỸ THUẬT CHI TIẾT --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-5 flex items-center border-b pb-3">
                    <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-microchip"></i></span>
                    Thông số kỹ thuật
                </h3>

                @if($product->thongSo)
                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full text-sm text-left">
                            <tbody class="divide-y divide-gray-100">
                                {{-- 1. CPU & Đồ họa --}}
                                <tr class="bg-gray-50/80"><td colspan="2" class="py-2.5 px-4 font-extrabold text-xs text-indigo-700 uppercase tracking-wider">Bộ xử lý & Đồ họa</td></tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 w-1/3 font-medium">Công nghệ CPU</td>
                                    <td class="py-3 px-4 text-gray-900 font-semibold">{{ $product->thongSo->cong_nghe_cpu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Số nhân / Luồng</td>
                                    <td class="py-3 px-4 text-gray-900">{{ $product->thongSo->so_nhan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Card đồ họa (VGA)</td>
                                    <td class="py-3 px-4 text-gray-900 font-semibold">{{ $product->thongSo->loai_card_do_hoa ?? '-' }}</td>
                                </tr>

                                {{-- 2. RAM & Ổ cứng --}}
                                <tr class="bg-gray-50/80"><td colspan="2" class="py-2.5 px-4 font-extrabold text-xs text-indigo-700 uppercase tracking-wider">RAM & Lưu trữ</td></tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">RAM</td>
                                    <td class="py-3 px-4 text-gray-900 font-semibold">{{ $product->thongSo->ram ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Ổ cứng</td>
                                    <td class="py-3 px-4 text-gray-900 font-semibold">{{ $product->thongSo->o_cung ?? '-' }}</td>
                                </tr>

                                {{-- 3. Màn hình --}}
                                <tr class="bg-gray-50/80"><td colspan="2" class="py-2.5 px-4 font-extrabold text-xs text-indigo-700 uppercase tracking-wider">Màn hình hiển thị</td></tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Kích thước & Độ phân giải</td>
                                    <td class="py-3 px-4 text-gray-900">
                                        {{ $product->thongSo->kich_thuoc_man_hinh ?? '' }} 
                                        @if($product->thongSo->do_phan_giai) - {{ $product->thongSo->do_phan_giai }} @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Công nghệ</td>
                                    <td class="py-3 px-4 text-gray-900">
                                        {{ $product->thongSo->cong_nghe_man_hinh ?? '-' }} 
                                        @if($product->thongSo->tan_so_quet) | {{ $product->thongSo->tan_so_quet }} @endif
                                        @if($product->thongSo->tam_nen) | {{ $product->thongSo->tam_nen }} @endif
                                    </td>
                                </tr>

                                {{-- 4. Khác --}}
                                <tr class="bg-gray-50/80"><td colspan="2" class="py-2.5 px-4 font-extrabold text-xs text-indigo-700 uppercase tracking-wider">Thiết kế & Khác</td></tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Kích thước & Trọng lượng</td>
                                    <td class="py-3 px-4 text-gray-900">
                                        {{ $product->thongSo->kich_thuoc ?? '-' }} | {{ $product->thongSo->trong_luong ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Chất liệu</td>
                                    <td class="py-3 px-4 text-gray-900">{{ $product->thongSo->chat_lieu ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Pin / HĐH</td>
                                    <td class="py-3 px-4 text-gray-900">
                                        {{ $product->thongSo->pin ?? '-' }} / {{ $product->thongSo->he_dieu_hanh ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-gray-500 font-medium">Cổng kết nối</td>
                                    <td class="py-3 px-4 text-gray-900">{{ $product->thongSo->cong_giao_tiep ?? '-' }}</td>
                                </tr>

                                {{-- Thông số tùy chỉnh --}}
                                @if(!empty($product->thongSo->thong_so_tuy_chinh) && is_array($product->thongSo->thong_so_tuy_chinh))
                                    <tr class="bg-gray-50/80"><td colspan="2" class="py-2.5 px-4 font-extrabold text-xs text-green-700 uppercase tracking-wider">Thông số mở rộng</td></tr>
                                    @foreach($product->thongSo->thong_so_tuy_chinh as $item)
                                        <tr>
                                            <td class="py-3 px-4 text-gray-500 font-medium">{{ $item['key'] ?? '---' }}</td>
                                            <td class="py-3 px-4 text-gray-900">{{ $item['val'] ?? '---' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-500 mb-2">Chưa có thông số kỹ thuật.</p>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:underline text-sm font-bold">Cập nhật ngay</a>
                    </div>
                @endif
            </div>

            {{-- CARD 6: BÀI VIẾT MÔ TẢ (TÁCH RIÊNG NHƯ YÊU CẦU) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-5 flex items-center border-b pb-3">
                    <span class="bg-orange-100 text-orange-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-align-left"></i></span>
                    Bài viết mô tả chi tiết
                </h3>
                
                <div class="prose prose-sm max-w-none text-gray-700">
                    {{-- Nội dung chính --}}
                    @if($product->mo_ta)
                        {!! $product->mo_ta !!}
                    @else
                        <p class="text-center text-gray-400 italic py-8 border border-dashed rounded-lg">Chưa có bài viết mô tả cho sản phẩm này.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection