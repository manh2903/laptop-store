{{-- Modal Xác Nhận Xóa --}}
<div id="delete-modal" class="fixed inset-0 z-[10000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    
    {{-- 1. Backdrop --}}
    <div onclick="closeDeleteModal()" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

    {{-- 2. Hộp thoại --}}
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 relative z-10 overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-slow">
                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác.</p>
            
            <div class="flex gap-3 justify-center">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">Hủy bỏ</button>
                <button type="button" id="confirm-delete-btn" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg shadow-red-500/30 transition-all transform hover:scale-105">Đồng ý xóa</button>
            </div>
        </div>
        <div class="h-1.5 w-full bg-gradient-to-r from-red-500 to-orange-500"></div>
    </div>
</div>

<script>
    let targetAction = "";     // Lưu đường dẫn xóa
    let targetCsrf = "";       // Lưu mã token bảo mật
    const deleteModal = document.getElementById('delete-modal');
    const modalContent = deleteModal ? deleteModal.querySelector('div.bg-white') : null;

    document.addEventListener('DOMContentLoaded', function() {
        if(!deleteModal) return;

        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            // Tìm input _method=DELETE để nhận diện form xóa
            const methodInput = form.querySelector('input[name="_method"][value="DELETE"]');
            if (methodInput) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); 
                    
                    // 1. Lấy thông tin từ form cũ
                    targetAction = form.action;
                    // Lấy token từ input _token hoặc meta tag
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

    // XỬ LÝ NÚT ĐỒNG Ý (CÁCH MỚI: TẠO FORM ẢO)
    const confirmBtn = document.getElementById('confirm-delete-btn');
    if(confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (targetAction) {
                // 1. Đóng modal & Hiện loading
                closeDeleteModal();
                if (typeof showLoading === 'function') showLoading();

                // 2. Tạo một form ảo hoàn toàn mới
                const newForm = document.createElement('form');
                newForm.method = 'POST';
                newForm.action = targetAction;
                newForm.style.display = 'none'; // Giấu nó đi

                // 3. Thêm Token bảo mật
                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = targetCsrf;
                newForm.appendChild(tokenField);

                // 4. Thêm Method DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                newForm.appendChild(methodField);

                // 5. Gắn vào trang và Gửi ngay lập tức
                document.body.appendChild(newForm);
                newForm.submit();
            }
        });
    }
</script>

<style>
    @keyframes bounce-slow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .animate-bounce-slow { animation: bounce-slow 2s infinite; }
</style>