@extends('admin.layouts.main')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="min-h-screen bg-gray-50/50 p-6 md:p-8">
    
    {{-- HEADER --}}
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">Thêm Sản Phẩm Mới</h1>
            <p class="text-sm text-gray-500 mt-2 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-green-500"></i>
                Nhập thông tin sản phẩm đầy đủ bên dưới
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 font-semibold text-sm transition shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Hủy bỏ
            </a>
            <button type="submit" form="create-product-form" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl shadow-lg hover:shadow-green-500/30 font-semibold text-sm flex items-center transition transform hover:scale-105">
                <i class="fas fa-save mr-2"></i> Lưu Sản Phẩm
            </button>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="create-product-form" class="max-w-7xl mx-auto space-y-8">
        @csrf

        {{-- ==================================================================================== --}}
        {{-- 1. CÀI ĐẶT & PHÂN LOẠI --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-cog"></i></span>
                Cài đặt hiển thị & Phân loại
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Trạng thái --}}
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-700">Trạng thái</label>
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50 cursor-pointer hover:bg-white hover:border-indigo-300 transition-all shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg"><i class="fas fa-store"></i></span>
                            <span class="font-bold text-gray-700">Đang kinh doanh</span>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            {{-- Mặc định checked cho sản phẩm mới --}}
                            <input type="checkbox" name="trang_thai" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                    </label>

                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center justify-center gap-2 p-2 border border-yellow-200 bg-yellow-50/50 rounded-lg cursor-pointer hover:bg-yellow-100 transition">
                            <span class="text-xs font-bold text-yellow-800">Flash Sale</span>
                            <input type="checkbox" name="is_flash_sale" value="1" class="rounded text-yellow-600 focus:ring-yellow-500">
                        </label>
                        <label class="flex-1 flex items-center justify-center gap-2 p-2 border border-blue-200 bg-blue-50/50 rounded-lg cursor-pointer hover:bg-blue-100 transition">
                            <span class="text-xs font-bold text-blue-800">Trả góp 0%</span>
                            <input type="checkbox" name="tra_gop_0_phan_tram" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>

                {{-- Danh mục --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Danh mục</label>
                    <select name="id_danh_muc" class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cate)
                            <option value="{{ $cate->id }}" {{ old('id_danh_muc') == $cate->id ? 'selected' : '' }}>{{ $cate->ten_danh_muc }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Thương hiệu --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thương hiệu</label>
                    <select name="id_thuong_hieu" class="w-full p-3 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        <option value="">-- Chọn thương hiệu --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('id_thuong_hieu') == $brand->id ? 'selected' : '' }}>{{ $brand->ten_thuong_hieu }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 2. THÔNG TIN CƠ BẢN & GIÁ --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div> 
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                <span class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-info"></i></span>
                Thông tin sản phẩm & Giá bán
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                <div class="md:col-span-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" name="ten_san_pham" value="{{ old('ten_san_pham') }}" class="w-full p-3 rounded-lg border border-gray-300 font-medium text-lg shadow-sm focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 transition" placeholder="VD: MacBook Air M2 2022 8GB 256GB" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mã SKU</label>
                    <input type="text" name="ma_sku" value="{{ old('ma_sku') }}" class="w-full p-3 rounded-lg border border-gray-300 bg-gray-50" placeholder="Tự động nếu để trống">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tồn kho <span class="text-red-500">*</span></label>
                    <input type="number" name="so_luong_ton" value="{{ old('so_luong_ton', 10) }}" class="w-full p-3 rounded-lg border border-gray-300 font-bold text-center focus:ring-indigo-200 focus:border-indigo-500" required>
                </div>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                <label class="block text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide"><i class="fas fa-tags text-green-600 mr-2"></i> Thiết lập giá bán</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start mb-6 border-b border-gray-200 pb-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Giá Gốc (Niêm yết)</label>
                        <div class="relative">
                            <input type="number" id="gia_ban" name="gia_ban" value="{{ old('gia_ban') }}" class="w-full p-3 pr-12 rounded-lg border border-gray-300 bg-white font-semibold text-gray-500 shadow-sm focus:border-indigo-500" placeholder="0" required oninput="calculateDiscount()">
                            <span class="absolute right-4 top-3 text-gray-400 font-bold text-sm">VNĐ</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-green-700 mb-1 uppercase">Giá Khuyến Mãi (Hiển thị)</label>
                        <div class="relative">
                            <input type="number" id="gia_khuyen_mai" name="gia_khuyen_mai" value="{{ old('gia_khuyen_mai') }}" class="w-full p-3 pr-24 rounded-lg border-2 border-green-500 bg-green-50 font-extrabold text-green-700 text-xl shadow-sm focus:ring-green-200 focus:border-green-600" placeholder="0" oninput="calculateDiscount()">
                            <span class="absolute right-14 top-4 text-green-600 font-bold text-sm">VNĐ</span>
                            <div id="discount-badge" class="absolute -top-3 -right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md hidden animate-bounce">-0%</div>
                        </div>
                        <p id="discount-text" class="text-xs text-red-500 font-bold mt-1 text-right h-4"></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-orange-600 mb-1">Giá "Thu cũ lên đời chỉ từ"</label>
                        <input type="number" name="gia_thu_cu" value="{{ old('gia_thu_cu') }}" class="w-full p-3 rounded-lg border border-orange-200 text-sm focus:border-orange-500 focus:ring-orange-200 shadow-sm" placeholder="VD: 16500000">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-purple-600 mb-1">Mức trợ giá (Số tiền)</label>
                        <input type="number" name="tro_gia" value="{{ old('tro_gia') }}" class="w-full p-3 rounded-lg border border-purple-200 text-sm focus:border-purple-500 focus:ring-purple-200 shadow-sm" placeholder="VD: 1500000">
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 3. KHUYẾN MÃI --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-pink-500"></div>
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                <span class="bg-pink-100 text-pink-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-gift"></i></span>
                Thông tin Khuyến mãi & Quà tặng
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Thông tin nổi bật (VD: Tặng Balo...)</label>
                    <input type="text" name="thong_tin_them" value="{{ old('thong_tin_them') }}" class="w-full p-3 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-pink-200 shadow-sm" placeholder="Tặng kèm Balo chống sốc...">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Coupon / Voucher</label>
                    <input type="text" name="uu_dai_khac" value="{{ old('uu_dai_khac') }}" class="w-full p-3 rounded-lg border border-gray-300 focus:border-pink-500 focus:ring-pink-200 shadow-sm" placeholder="Giảm thêm 500k qua VNPAY...">
                </div>
            </div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 4. THÔNG SỐ KỸ THUẬT (EMPTY) --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 relative">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-500"></div>
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                <span class="bg-purple-100 text-purple-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-microchip"></i></span>
                Thông số kỹ thuật chi tiết
            </h3>

            {{-- GROUP 1: CPU & ĐỒ HỌA --}}
            <div class="bg-white border border-gray-200 rounded-xl mb-6 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center font-bold text-gray-700">
                    <i class="fas fa-server mr-2 text-indigo-500"></i> 1. Bộ xử lý & Đồ họa
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Công nghệ CPU</label>
                        <input type="text" name="cong_nghe_cpu" value="{{ old('cong_nghe_cpu') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Intel Core i5">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Số nhân / luồng</label>
                        <input type="text" name="so_nhan" value="{{ old('so_nhan') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 8 nhân">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Card đồ họa (VGA)</label>
                        <input type="text" name="loai_card_do_hoa" value="{{ old('loai_card_do_hoa') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: NVIDIA RTX 3050">
                    </div>
                </div>
            </div>

            {{-- GROUP 2: RAM & Ổ CỨNG --}}
            <div class="bg-white border border-gray-200 rounded-xl mb-6 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center font-bold text-gray-700">
                    <i class="fas fa-hdd mr-2 text-green-500"></i> 2. RAM & Ổ cứng
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">RAM mặc định</label>
                        <input type="text" name="ram" value="{{ old('ram') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 16 GB">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Ổ cứng mặc định</label>
                        <input type="text" name="o_cung" value="{{ old('o_cung') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 512GB SSD">
                    </div>
                </div>
            </div>

            {{-- GROUP 3: MÀN HÌNH --}}
            <div class="bg-white border border-gray-200 rounded-xl mb-6 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center font-bold text-gray-700">
                    <i class="fas fa-desktop mr-2 text-blue-500"></i> 3. Màn hình
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Kích thước màn hình</label>
                        <input type="text" name="kich_thuoc_man_hinh" value="{{ old('kich_thuoc_man_hinh') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 15.6 inch">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Độ phân giải</label>
                        <input type="text" name="do_phan_giai" value="{{ old('do_phan_giai') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Full HD (1920x1080)">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Công nghệ màn hình</label>
                        <input type="text" name="cong_nghe_man_hinh" value="{{ old('cong_nghe_man_hinh') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Anti-Glare">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Tấm nền / Tần số quét</label>
                        <input type="text" name="tan_so_quet" value="{{ old('tan_so_quet') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: IPS, 144Hz">
                    </div>
                </div>
            </div>

            {{-- GROUP 4: THIẾT KẾ & PIN --}}
            <div class="bg-white border border-gray-200 rounded-xl mb-6 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center font-bold text-gray-700">
                    <i class="fas fa-ruler-combined mr-2 text-orange-500"></i> 4. Thiết kế & Pin
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Chất liệu</label>
                        <input type="text" name="chat_lieu" value="{{ old('chat_lieu') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Kim loại nguyên khối">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Trọng lượng (kg)</label>
                        <input type="text" name="trong_luong" value="{{ old('trong_luong') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 1.7 kg">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Kích thước máy</label>
                        <input type="text" name="kich_thuoc" value="{{ old('kich_thuoc') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Pin</label>
                        <input type="text" name="pin" value="{{ old('pin') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 3 Cell, 50Wh">
                    </div>
                </div>
            </div>

            {{-- GROUP 5: KẾT NỐI & TIỆN ÍCH --}}
            <div class="bg-white border border-gray-200 rounded-xl mb-6 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex items-center font-bold text-gray-700">
                    <i class="fas fa-wifi mr-2 text-purple-500"></i> 5. Kết nối & Tiện ích
                </div>
                <div class="p-4 grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-1 block">Cổng giao tiếp</label>
                        <input type="text" name="cong_giao_tiep" value="{{ old('cong_giao_tiep') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: 2x USB 3.2, 1x HDMI, 1x Jack 3.5mm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Kết nối không dây</label>
                            <input type="text" name="ket_noi_khong_day" value="{{ old('ket_noi_khong_day') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Wi-Fi 6, Bluetooth 5.1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Webcam</label>
                            <input type="text" name="webcam" value="{{ old('webcam') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: HD 720p">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Công nghệ âm thanh</label>
                            <input type="text" name="cong_nghe_am_thanh" value="{{ old('cong_nghe_am_thanh') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Dolby Atmos">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Hệ điều hành</label>
                            <input type="text" name="he_dieu_hanh" value="{{ old('he_dieu_hanh') }}" class="w-full p-2 text-sm border border-gray-300 rounded focus:border-indigo-500" placeholder="Vd: Windows 11 Home">
                        </div>
                    </div>
                </div>
            </div>

            {{-- GROUP 6: CUSTOM SPECS --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-bold text-sm text-indigo-700 flex items-center uppercase">
                        <i class="fas fa-plus-circle mr-2"></i> Thông số mở rộng (Tùy chỉnh)
                    </h4>
                    <button type="button" id="btnAddSpec" class="bg-indigo-600 text-white text-xs px-3 py-1.5 rounded shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-plus mr-1"></i> Thêm dòng
                    </button>
                </div>
                <div id="dynamicSpecsContainer" class="space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    {{-- Chưa có dữ liệu nên để trống, JS sẽ append vào đây --}}
                </div>
            </div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 5. NỘI DUNG --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 relative">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-orange-500"></div>
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                <span class="bg-orange-100 text-orange-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-pen-nib"></i></span>
                Nội dung bài viết
            </h3>
            <div class="mb-6">
                <label class="block text-sm font-bold text-orange-800 mb-2">Nội dung "Tính năng nổi bật" (Ngắn)</label>
                <textarea name="tinh_nang_noi_bat" rows="5" class="w-full p-3 rounded-lg border border-orange-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-100">{{ old('tinh_nang_noi_bat') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Bài viết mô tả chi tiết</label>
                <textarea name="mo_ta" id="editor_mo_ta" rows="10">{{ old('mo_ta') }}</textarea>
            </div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 6. MEDIA --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 relative">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-500"></div>
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center border-b pb-3">
                <span class="bg-slate-100 text-slate-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-images"></i></span>
                Quản lý Media
            </h3>
            
            {{-- MỚI: ẢNH ĐẠI DIỆN --}}
            <div class="mb-6 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <label class="block text-sm font-bold text-indigo-800 mb-3"><i class="fas fa-id-badge mr-2"></i> Ảnh đại diện (Thumbnail)</label>
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    {{-- Preview Box --}}
                    <div class="relative w-32 h-32 bg-white border-2 border-dashed border-indigo-300 rounded-lg flex items-center justify-center overflow-hidden shrink-0 group" id="avatarPreviewBox">
                        <div class="text-center text-indigo-300">
                            <i class="fas fa-image text-2xl mb-1"></i>
                            <span class="text-[10px] block">Chưa có ảnh</span>
                        </div>
                    </div>
                    
                    {{-- Input Upload --}}
                    <div class="flex-1 w-full">
                        <input type="file" name="anh_dai_dien" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 transition cursor-pointer" onchange="previewAvatar(this)" required>
                        <p class="text-xs text-gray-500 mt-2">Chọn ảnh đại diện (Bắt buộc). Định dạng: JPG, PNG, WEBP.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Video --}}
                <div class="bg-pink-50 p-4 rounded-xl border border-pink-100">
                    <label class="block text-sm font-bold text-pink-700 mb-2"><i class="fas fa-video mr-1"></i> Video Sản phẩm</label>
                    <input type="file" name="video_product" accept="video/*" class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-100 file:text-pink-700 hover:file:bg-pink-200">
                </div>
            </div>

            {{-- Feature Images --}}
            <div class="mb-6 bg-orange-50 p-4 rounded-xl border border-orange-200">
                <label class="block text-sm font-bold text-orange-800 mb-2"><i class="fas fa-star mr-2"></i> Ảnh Tính Năng (Feature)</label>
                <input type="file" name="anh_tinh_nang[]" multiple class="mb-3 w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-100 file:text-orange-700 hover:file:bg-orange-200">
            </div>

            {{-- Gallery 3 Sizes --}}
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-bold text-blue-800"><i class="fas fa-layer-group mr-2"></i> Album Ảnh (3 Size)</label>
                    <button type="button" id="btnAddGalleryRow" class="bg-blue-600 text-white text-xs px-3 py-2 rounded shadow hover:bg-blue-700 transition"><i class="fas fa-plus mr-1"></i> Thêm bộ ảnh</button>
                </div>
                <div id="galleryUploadContainer" class="space-y-2"></div>
            </div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 7. QUẢN LÝ BIẾN THỂ (MÀU SẮC) --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <span class="bg-purple-100 text-purple-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-palette"></i></span>
                    Cấu hình Màu sắc & Giá bán
                </h3>
                <button type="button" id="btnAddVariant" class="bg-purple-600 text-white text-xs px-3 py-2 rounded shadow hover:bg-purple-700 transition">
                    <i class="fas fa-plus mr-1"></i> Thêm màu mới
                </button>
            </div>
            <div id="variantContainer" class="space-y-4"></div>
        </div>

        {{-- ==================================================================================== --}}
        {{-- 8. QUẢN LÝ CẤU HÌNH NÂNG CẤP --}}
        {{-- ==================================================================================== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-10">
            <div class="flex justify-between items-center mb-6 border-b pb-3">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <span class="bg-green-100 text-green-600 w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-tools"></i></span>
                    Cấu hình Nâng cấp (Tự động sắp xếp)
                </h3>
                <button type="button" id="btnAddConfig" class="bg-green-600 text-white text-xs px-3 py-2 rounded shadow hover:bg-green-700 transition">
                    <i class="fas fa-plus mr-1"></i> Thêm tùy chọn
                </button>
            </div>
            <div id="newConfigContainer" class="mt-2 space-y-2"></div>
        </div>

    </form>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        CKEDITOR.replace('editor_mo_ta');

        // 1. Logic Gallery 3 Size
        document.getElementById('btnAddGalleryRow').addEventListener('click', function() {
            const container = document.getElementById('galleryUploadContainer');
            const div = document.createElement('div');
            div.className = 'grid grid-cols-3 gap-2 bg-white p-3 rounded-lg border border-dashed border-blue-400 relative group mt-2 shadow-sm';
            div.innerHTML = `
                <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs z-10 shadow hover:bg-red-600 transition" onclick="this.parentElement.remove()">×</button>
                <div class="bg-gray-50 p-2 rounded border text-center">
                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Ảnh Nhỏ</label>
                    <input type="file" name="gallery_small[]" class="w-full text-[9px]">
                </div>
                <div class="bg-blue-50 p-2 rounded border border-blue-200 text-center">
                    <label class="text-[10px] font-bold text-blue-600 block mb-1">Ảnh Thường *</label>
                    <input type="file" name="gallery_medium[]" class="w-full text-[9px]" required>
                </div>
                <div class="bg-gray-50 p-2 rounded border text-center">
                    <label class="text-[10px] font-bold text-gray-500 block mb-1">Ảnh Lớn</label>
                    <input type="file" name="gallery_large[]" class="w-full text-[9px]">
                </div>
            `;
            container.appendChild(div);
        });

        // 2. Logic thêm Màu Sắc Mới (Đã đồng bộ name: variants[])
        let variantIndex = 0;
        document.getElementById('btnAddVariant').addEventListener('click', function() {
            variantIndex++;
            const container = document.getElementById('variantContainer');
            const div = document.createElement('div');
            div.className = 'grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded-lg border-2 border-dashed border-purple-300 relative';
            div.innerHTML = `
                <button type="button" class="absolute -top-3 -right-3 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center text-xs shadow hover:bg-red-600 z-10 transition" onclick="this.parentElement.remove()">×</button>
                <div><label class="text-[11px] font-bold text-gray-500 block mb-1">1. Tên Màu</label><input type="text" name="variants[${variantIndex}][color]" class="block w-full text-sm border-gray-300 rounded focus:ring-purple-200 focus:border-purple-500" required placeholder="Nhập tên màu..."></div>
                <div><label class="text-[11px] font-bold text-gray-500 block mb-1">2. Giá tiền</label><input type="number" name="variants[${variantIndex}][price]" class="block w-full text-sm border-gray-300 rounded focus:ring-purple-200 focus:border-purple-500" required placeholder="Nhập giá..."></div>
                <div><label class="text-[11px] font-bold text-gray-500 block mb-1">3. Ảnh màu</label><input type="file" name="variants[${variantIndex}][image]" class="block w-full text-xs" required></div>
            `;
            container.appendChild(div);
        });

        // 3. Logic thêm Cấu hình Nâng cấp
        document.getElementById('btnAddConfig').addEventListener('click', function() {
            const container = document.getElementById('newConfigContainer');
            const div = document.createElement('div');
            const uniqueId = 'list_' + Math.random().toString(36).substr(2, 9);
            const idx = Date.now(); 

            div.className = 'grid grid-cols-1 md:grid-cols-3 gap-2 items-center bg-green-50 p-2 rounded border border-dashed border-green-300 relative';
            div.innerHTML = `
                <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs" onclick="this.parentElement.remove()">×</button>
                <div>
                    <select name="configs[${idx}][type]" class="w-full text-xs p-2 border border-gray-300 rounded focus:border-green-500" onchange="updateDatalist(this, '${uniqueId}')">
                        <option value="chip">Chip (CPU)</option>
                        <option value="ram">RAM</option>
                        <option value="ssd">SSD</option>
                        <option value="adapter">Adapter</option>
                    </select>
                </div>
                <div>
                    <input type="text" name="configs[${idx}][name]" list="${uniqueId}" class="w-full text-xs p-2 border border-gray-300 rounded focus:border-green-500" placeholder="Tên cấu hình (Có gợi ý)...">
                    <datalist id="${uniqueId}"></datalist>
                </div>
                <div><input type="number" name="configs[${idx}][price]" class="w-full text-xs p-2 border border-gray-300 rounded focus:border-green-500" value="0" placeholder="Giá thêm"></div>
            `;
            container.appendChild(div);
            const selectEl = div.querySelector('select');
            updateDatalist(selectEl, uniqueId);
        });

        // 4. Logic Thông số Tùy chỉnh (JSON)
        const containerSpec = document.getElementById('dynamicSpecsContainer');
        document.getElementById('btnAddSpec').addEventListener('click', function() {
            const currentIndex = containerSpec.querySelectorAll('.spec-row').length + 1000;
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center spec-row bg-white p-2 rounded border border-gray-200 animate-fade-in-down';
            div.innerHTML = `
                <input type="text" name="custom_specs[${currentIndex}][key]" class="w-1/3 p-2 text-sm border border-gray-300 rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200" placeholder="Tên thông số">
                <input type="text" name="custom_specs[${currentIndex}][val]" class="w-2/3 p-2 text-sm border border-gray-300 rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200" placeholder="Giá trị">
                <button type="button" class="text-red-400 hover:text-red-600 w-8 text-center btn-remove-spec transition"><i class="fas fa-times"></i></button>
            `;
            containerSpec.appendChild(div);
        });
        containerSpec.addEventListener('click', function(e) {
            if(e.target.closest('.btn-remove-spec')) e.target.closest('.spec-row').remove();
        });
    });

    // --- HÀM TÍNH PHẦN TRĂM GIẢM GIÁ ---
    function calculateDiscount() {
        const originalPrice = document.getElementById('gia_ban').value;
        const salePrice = document.getElementById('gia_khuyen_mai').value;
        const badge = document.getElementById('discount-badge');
        const text = document.getElementById('discount-text');

        if (originalPrice && salePrice && Number(originalPrice) > Number(salePrice)) {
            const percent = Math.round(((originalPrice - salePrice) / originalPrice) * 100);
            badge.innerText = `-${percent}%`;
            badge.classList.remove('hidden');
            text.innerText = `Giảm ${percent}% so với giá gốc`;
        } else {
            badge.classList.add('hidden');
            text.innerText = '';
        }
    }

    // --- XEM TRƯỚC ẢNH AVATAR ---
    function previewAvatar(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('avatarPreviewBox');
                // Hiển thị ảnh và nút xóa giả (chỉ xóa khỏi view, reset input)
                box.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-contain">
                    <button type="button" onclick="removePreviewAvatar()" class="absolute top-1 right-1 bg-gray-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs hover:bg-gray-600 transition">×</button>
                `;
            }
            reader.readAsDataURL(file);
        }
    }

    function removePreviewAvatar() {
        // Reset input file
        document.querySelector('input[name="anh_dai_dien"]').value = '';
        // Reset view
        document.getElementById('avatarPreviewBox').innerHTML = `
            <div class="text-center text-indigo-300">
                <i class="fas fa-image text-2xl mb-1"></i>
                <span class="text-[10px] block">Chưa có ảnh</span>
            </div>
        `;
    }

    // Helper: Update Datalist
    function updateDatalist(selectElement, listId) {
        const type = selectElement.value;
        const datalist = document.getElementById(listId);
        if(!datalist) return;
        datalist.innerHTML = ''; 
        const suggestions = {
            'chip': ['Apple M1', 'Apple M2', 'M2 Pro', 'M2 Max', 'Intel Core i5', 'Intel Core i7'],
            'ram':  ['8GB RAM', '16GB RAM', '24GB RAM', '32GB RAM', '64GB RAM'],
            'ssd':  ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD'],
            'adapter': ['Sạc USB-C 30W', 'Sạc Dual USB-C 35W', 'Sạc USB-C 67W', 'Sạc USB-C 140W']
        };
        if (suggestions[type]) {
            suggestions[type].forEach(item => {
                const opt = document.createElement('option');
                opt.value = item;
                datalist.appendChild(opt);
            });
        }
    }
</script>
@endsection