<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - LaptopTF</title>
    
    {{-- 1. ICON (FontAwesome) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- 2. TAILWIND CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a' }
                    }
                }
            }
        }
    </script>

    {{-- 3. CSS TÙY CHỈNH --}}
    <link rel="stylesheet" href="{{ asset('backend/asset/css/style.css') }}">

    @stack('css')
</head>

<body class="text-gray-800 h-screen overflow-hidden flex bg-gray-50">

    {{-- 🔥 1. CHÈN LOADER (Sửa đường dẫn thành admin.partials.loader) 🔥 --}}
    @include('admin.partials.loader')

    {{-- Gọi Sidebar --}}
    @include('admin.parts.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden relative">
        
        {{-- Gọi Header --}}
        @include('admin.parts.header')

        {{-- Nội dung chính --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/50 p-6 relative">
            
            {{-- 🔥 2. CHÈN THÔNG BÁO (Sửa đường dẫn thành admin.partials.notification) 🔥 --}}
            @include('admin.partials.notification')
            @include('admin.partials.delete_modal')

            @yield('content')
        </main>

    </div>
    
    {{-- 4. JS TÙY CHỈNH --}}
    <script src="{{ asset('backend/asset/js/main.js') }}"></script>

    @stack('scripts')

</body>
</html>