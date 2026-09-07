{{-- CSS Hiệu ứng Modern --}}
<style>
    /* Hiệu ứng trượt nảy (Bouncy Slide) */
    @keyframes slideInSpring {
        0% { transform: translateX(120%) scale(0.8); opacity: 0; }
        70% { transform: translateX(-10px) scale(1.02); opacity: 1; } /* Trượt quá một chút */
        100% { transform: translateX(0) scale(1); opacity: 1; }       /* Về vị trí chuẩn */
    }
    
    @keyframes fadeOutZoom {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(0.9) translateY(10px); }
    }

    @keyframes progressRun {
        from { width: 100%; }
        to { width: 0%; }
    }

    .toast-modern {
        animation: slideInSpring 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        backdrop-filter: blur(8px); /* Kính mờ nhẹ nếu nền có màu */
    }

    .toast-closing {
        animation: fadeOutZoom 0.4s ease forwards !important;
    }

    .toast-progress-bar {
        height: 3px;
        position: absolute;
        bottom: 0;
        left: 0;
        border-radius: 0 4px 4px 0; /* Bo góc phải cho đẹp */
        animation: progressRun 4s linear forwards; /* Tăng lên 4s cho người dùng kịp đọc */
    }
</style>

<div id="toast-container" class="fixed top-24 right-6 z-[9999] flex flex-col gap-4 pointer-events-none">
    
    {{-- 1. THÀNH CÔNG (Emerald Theme) --}}
    @if(session('success'))
    <div class="toast-modern pointer-events-auto relative w-96 bg-white/95 border border-emerald-100 shadow-2xl rounded-2xl overflow-hidden flex items-start p-4 group hover:shadow-emerald-100/50 transition-all duration-300">
        
        {{-- Icon Box --}}
        <div class="flex-shrink-0 mr-4">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shadow-inner">
                <i class="fas fa-check text-lg"></i>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 pr-6">
            <h4 class="font-bold text-gray-800 text-sm font-sans mb-1">Thành công!</h4>
            <p class="text-gray-500 text-xs leading-relaxed">{{ session('success') }}</p>
        </div>

        {{-- Close Button --}}
        <button onclick="closeToast(this)" class="absolute top-3 right-3 text-gray-300 hover:text-emerald-500 hover:rotate-90 transition-all duration-300 p-1">
            <i class="fas fa-times"></i>
        </button>

        {{-- Progress Bar (Gradient) --}}
        <div class="toast-progress-bar bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
    </div>
    @endif

    {{-- 2. LỖI (Rose Theme) --}}
    @if(session('error'))
    <div class="toast-modern pointer-events-auto relative w-96 bg-white/95 border border-red-100 shadow-2xl rounded-2xl overflow-hidden flex items-start p-4 group hover:shadow-red-100/50 transition-all duration-300">
        
        <div class="flex-shrink-0 mr-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 shadow-inner animate-pulse">
                <i class="fas fa-exclamation text-lg"></i>
            </div>
        </div>

        <div class="flex-1 pr-6">
            <h4 class="font-bold text-gray-800 text-sm font-sans mb-1">Opps! Có lỗi rồi</h4>
            <p class="text-gray-500 text-xs leading-relaxed">{{ session('error') }}</p>
        </div>

        <button onclick="closeToast(this)" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 hover:rotate-90 transition-all duration-300 p-1">
            <i class="fas fa-times"></i>
        </button>

        <div class="toast-progress-bar bg-gradient-to-r from-red-400 to-red-600"></div>
    </div>
    @endif

</div>

<template id="toast-success-template">
    <div class="toast-modern pointer-events-auto relative w-96 bg-white/95 border border-emerald-100 shadow-2xl rounded-2xl overflow-hidden flex items-start p-4 group">
        <div class="flex-shrink-0 mr-4">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shadow-inner">
                <i class="fas fa-check text-lg"></i>
            </div>
        </div>
        <div class="flex-1 pr-6">
            <h4 class="font-bold text-gray-800 text-sm font-sans mb-1">Thành công!</h4>
            <p class="toast-msg-text text-gray-500 text-xs leading-relaxed"></p> {{-- Nội dung sẽ được điền vào đây --}}
        </div>
        <button onclick="closeToast(this)" class="absolute top-3 right-3 text-gray-300 hover:text-emerald-500 transition-all duration-300 p-1">
            <i class="fas fa-times"></i>
        </button>
        <div class="toast-progress-bar bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
    </div>
</template>

<script>
    // Tự động tắt sau 4 giây
    setTimeout(() => {
        const toasts = document.querySelectorAll('.toast-modern');
        toasts.forEach(t => closeToast(t));
    }, 4000);

    function closeToast(element) {
        // Tìm phần tử cha chứa class toast-modern
        const toast = element.closest('.toast-modern');
        if(toast) {
            toast.classList.add('toast-closing'); // Kích hoạt animation ẩn
            
            // Đợi animation chạy xong (400ms) rồi mới xóa khỏi DOM
            setTimeout(() => {
                toast.remove();
            }, 400); 
        }
    }
</script>