@extends('admin.layouts.main')

@section('title', 'Bảng điều khiển')

@section('content')
<div class="min-h-screen p-6">
    
    {{-- 1. HEADER CHÀO MỪNG --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Xin chào, Admin! 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Đây là tổng quan tình hình kinh doanh hôm nay.</p>
        </div>
        <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
            <i class="far fa-calendar-alt mr-2"></i> {{ date('d/m/Y') }}
        </div>
    </div>

    {{-- 2. CÁC THẺ THỐNG KÊ (STATS CARDS) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 h-24 w-24 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-purple-100"></div>
            <div class="relative z-10">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tổng Doanh Thu</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalRevenue) }}đ</h3>
                <div class="flex items-center mt-2 text-xs font-medium text-green-600 bg-green-50 w-fit px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i> +12.5% so với tháng trước
                </div>
            </div>
            <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 absolute bottom-6 right-6">
                <i class="fas fa-sack-dollar text-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 h-24 w-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-blue-100"></div>
            <div class="relative z-10">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Đơn Hàng Mới</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</h3>
                <div class="flex items-center mt-2 text-xs font-medium text-blue-600 bg-blue-50 w-fit px-2 py-1 rounded">
                    <i class="fas fa-shopping-cart mr-1"></i> Đang xử lý
                </div>
            </div>
            <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 absolute bottom-6 right-6">
                <i class="fas fa-receipt text-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 h-24 w-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-orange-100"></div>
            <div class="relative z-10">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tổng Sản Phẩm</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</h3>
                <div class="flex items-center mt-2 text-xs font-medium text-gray-500">
                    Trong {{ $totalCategories }} danh mục
                </div>
            </div>
            <div class="h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 absolute bottom-6 right-6">
                <i class="fas fa-box-open text-lg"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 h-24 w-24 bg-pink-50 rounded-bl-full -mr-4 -mt-4 transition group-hover:bg-pink-100"></div>
            <div class="relative z-10">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Khách Hàng</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomers }}</h3>
                <div class="flex items-center mt-2 text-xs font-medium text-green-600 bg-green-50 w-fit px-2 py-1 rounded">
                    <i class="fas fa-user-plus mr-1"></i> +5 mới hôm nay
                </div>
            </div>
            <div class="h-10 w-10 bg-pink-100 rounded-full flex items-center justify-center text-pink-600 absolute bottom-6 right-6">
                <i class="fas fa-users text-lg"></i>
            </div>
        </div>
    </div>

    {{-- 3. LAYOUT CHÍNH (BIỂU ĐỒ & DANH SÁCH) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Cột Trái: Biểu đồ doanh thu (Giả lập bằng CSS) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800">Biểu đồ doanh thu tuần này</h3>
                <select class="text-xs border-gray-200 border rounded-lg p-2 bg-gray-50 outline-none">
                    <option>7 ngày qua</option>
                    <option>Tháng này</option>
                    <option>Năm nay</option>
                </select>
            </div>
            
            {{-- Chart Area --}}
            <div class="h-64 flex items-end justify-between gap-2 px-2">
                {{-- Cột 1 --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-lg h-32 relative group-hover:bg-indigo-500 transition-all duration-300">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition">3.2tr</div>
                    </div>
                    <span class="text-xs text-gray-400 mt-2">T2</span>
                </div>
                {{-- Cột 2 --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-lg h-48 relative group-hover:bg-indigo-500 transition-all duration-300">
                         <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition">5.1tr</div>
                    </div>
                    <span class="text-xs text-gray-400 mt-2">T3</span>
                </div>
                {{-- Cột 3 --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-lg h-24 relative group-hover:bg-indigo-500 transition-all duration-300"></div>
                    <span class="text-xs text-gray-400 mt-2">T4</span>
                </div>
                {{-- Cột 4 (Cao nhất) --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-500 rounded-t-lg h-56 relative shadow-lg">
                         <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded">6.8tr</div>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 mt-2">T5</span>
                </div>
                {{-- Cột 5 --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-lg h-40 relative group-hover:bg-indigo-500 transition-all duration-300"></div>
                    <span class="text-xs text-gray-400 mt-2">T6</span>
                </div>
                {{-- Cột 6 --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-lg h-36 relative group-hover:bg-indigo-500 transition-all duration-300"></div>
                    <span class="text-xs text-gray-400 mt-2">T7</span>
                </div>
                {{-- Cột 7 --}}
                <div class="w-full flex flex-col items-center group">
                    <div class="w-full bg-indigo-100 rounded-t-lg h-44 relative group-hover:bg-indigo-500 transition-all duration-300"></div>
                    <span class="text-xs text-gray-400 mt-2">CN</span>
                </div>
            </div>
        </div>

        {{-- Cột Phải: Sản phẩm vừa nhập --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4">Sản phẩm mới nhập</h3>
            <div class="space-y-4">
                @foreach($newestProducts as $sp)
                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition border border-transparent hover:border-gray-100">
                    <div class="h-12 w-12 bg-gray-100 rounded-lg flex-shrink-0 border border-gray-200 overflow-hidden">
                        <img src="{{ asset('storage/' . $sp->hinh_anh) }}" alt="img" class="h-full w-full object-contain">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $sp->ten_sp }}</p>
                        <p class="text-xs text-indigo-600 font-bold">{{ number_format($sp->gia_ban) }}đ</p>
                    </div>
                    <a href="#" class="text-gray-400 hover:text-indigo-600"><i class="fas fa-chevron-right"></i></a>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <a href="#" class="text-sm text-indigo-600 font-medium hover:underline">Xem tất cả sản phẩm</a>
            </div>
        </div>

    </div>
</div>
@endsection