<aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl flex-shrink-0 transition-all duration-300">
    {{-- Logo --}}
    <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-900">
        <span class="text-xl font-bold tracking-wider text-blue-400">LAPTOP<span class="text-white">TF</span></span>
    </div>

    {{-- Menu List --}}
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            {{-- Giữ nguyên các menu của bạn ở đây --}}
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition group">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition group">
                    <i class="fas fa-layer-group w-5 text-center"></i>
                    <span class="font-medium">Danh mục</span>
                </a>
            </li>
            <li>
    <a href="{{ route('admin.brands.index') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.brands.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition group">
        
        {{-- Icon --}}
        <i class="fas fa-tags w-5 text-center"></i>
        
        {{-- Chữ --}}
        <span class="font-medium">Thương hiệu</span>
    </a>
</li>
            <li>
                <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition group">
                    <i class="fas fa-laptop w-5 text-center"></i>
                    <span class="font-medium">Sản phẩm</span>
                </a>
            </li>
             <li>
    <a href="{{ Route::has('admin.carts.index') ? route('admin.carts.index') : '#' }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.carts.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition group">
        <i class="fas fa-shopping-cart w-5 text-center"></i> {{-- Đổi icon sang giỏ hàng --}}
        <span class="font-medium">Giỏ hàng chờ</span>
    </a>
</li>
        </ul>
    </nav>
</aside>