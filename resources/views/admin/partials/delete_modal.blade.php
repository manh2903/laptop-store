{{-- Modal Xác Nhận Xóa --}}
<div id="delete-modal" class="fixed inset-0 z-[10000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    
    {{-- 1. Backdrop mờ (Click ra ngoài để hủy) --}}
    <div onclick="closeDeleteModal()" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

    {{-- 2. Hộp thoại chính --}}
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 relative z-10 overflow-hidden">
        
        <div class="p-6 text-center">
            {{-- Icon Cảnh Báo --}}
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-slow">
                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác. Dữ liệu sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            
            {{-- Nút bấm --}}
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    Hủy bỏ
                </button>
                <button type="button" id="confirm-delete-btn" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg shadow-red-500/30 transition-all transform hover:scale-105">
                    Đồng ý xóa
                </button>
            </div>
        </div>
        
        {{-- Thanh trang trí bên dưới --}}
        <div class="h-1.5 w-full bg-gradient-to-r from-red-500 to-orange-500"></div>
    </div>
</div>

{{-- (Phần HTML ở trên giữ nguyên) --}}

<script>
    let targetAction = "";     
    let targetCsrf = "";       
    const deleteModal = document.getElementById('delete-modal');
    const modalContent = deleteModal ? deleteModal.querySelector('div.bg-white') : null;

    document.addEventListener('DOMContentLoaded', function() {
        if(!deleteModal) return;

        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const methodInput = form.querySelector('input[name="_method"][value="DELETE"]');
            if (methodInput) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); 
                    targetAction = form.action;
                    const csrfInput = form.querySelector('input[name="_token"]');
                    targetCsrf = csrfInput ? csrfInput.value : document.querySelector('meta[name="csrf-token"]')?.content;
                    openDeleteModal();
                });
            }
        });
    });

    // Hàm mở/đóng modal
    function openDeleteModal() {
        if(!deleteModal) return;
        deleteModal.classList.remove('opacity-0', 'pointer-events-none');
        if(modalContent) { modalContent.classList.remove('scale-95'); modalContent.classList.add('scale-100'); }
    }
    function closeDeleteModal() {
        if(!deleteModal) return;
        deleteModal.classList.add('opacity-0', 'pointer-events-none');
        if(modalContent) { modalContent.classList.remove('scale-100'); modalContent.classList.add('scale-95'); }
    }

    // XỬ LÝ NÚT ĐỒNG Ý (Loading theo mạng thực tế)
    const confirmBtn = document.getElementById('confirm-delete-btn');
    if(confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (targetAction) {
                // 1. Đóng Modal Xóa ngay lập tức
                closeDeleteModal();

                // 2. Hiện màn hình Loading ngay
                if (typeof showLoading === 'function') showLoading();

                // 3. Đợi một xíu (300ms) để Modal kịp tắt hẳn cho đẹp mắt
                // Sau đó gửi lệnh xóa đi ngay lập tức
                setTimeout(function() {
                    
                    const newForm = document.createElement('form');
                    newForm.method = 'POST';
                    newForm.action = targetAction;
                    newForm.style.display = 'none'; 

                    const tokenField = document.createElement('input');
                    tokenField.type = 'hidden';
                    tokenField.name = '_token';
                    tokenField.value = targetCsrf;
                    newForm.appendChild(tokenField);

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    newForm.appendChild(methodField);

                    document.body.appendChild(newForm);
                    
                    // Gửi đi ngay! Trình duyệt sẽ giữ màn hình Loading
                    // cho đến khi Server phản hồi xong và load trang mới.
                    newForm.submit(); 

                }, 300); // 300ms = Thời gian animation tắt modal
            }
        });
    }
</script>

{{-- Style cho icon nảy --}}
<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s infinite;
    }
</style>