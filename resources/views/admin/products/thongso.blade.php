{{-- Nội dung modal thông số sản phẩm --}}
<div class="modal-overlay">
    <form id="quickSpecsForm" class="modern-specs-container">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        {{-- Header --}}
        <div class="specs-header">
            <button type="button" onclick="closeSpecsModal()" class="close-button">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="header-content">
                <div class="laptop-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="header-text">
                    <h2 class="header-title">Thông Số Kỹ Thuật</h2>
                    <p class="product-name">{{ $product->ten_san_pham }}</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="specs-body">
            @php $s = $product->specification; @endphp

            <div class="specs-grid">
                {{-- CPU --}}
                <div class="spec-card">
                    <div class="spec-icon blue">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">CPU</label>
                        <input type="text" name="cpu" value="{{ $s->cpu ?? '' }}" 
                               class="spec-input" placeholder="Nhập thông tin CPU">
                    </div>
                </div>

                {{-- RAM --}}
                <div class="spec-card">
                    <div class="spec-icon green">
                        <i class="fas fa-memory"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">RAM</label>
                        <input type="text" name="ram" value="{{ $s->ram ?? '' }}" 
                               class="spec-input" placeholder="Nhập dung lượng RAM">
                    </div>
                </div>

                {{-- Ổ cứng --}}
                <div class="spec-card">
                    <div class="spec-icon purple">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Ổ Cứng</label>
                        <input type="text" name="o_cung" value="{{ $s->o_cung ?? '' }}" 
                               class="spec-input" placeholder="Nhập dung lượng ổ cứng">
                    </div>
                </div>

                {{-- Card đồ họa --}}
                <div class="spec-card">
                    <div class="spec-icon orange">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Card Đồ Họa</label>
                        <input type="text" name="card_do_hoa" value="{{ $s->card_do_hoa ?? '' }}" 
                               class="spec-input" placeholder="Nhập thông tin card đồ họa">
                    </div>
                </div>

                {{-- Màn hình --}}
                <div class="spec-card">
                    <div class="spec-icon indigo">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Màn Hình</label>
                        <input type="text" name="man_hinh" value="{{ $s->man_hinh ?? '' }}" 
                               class="spec-input" placeholder="Nhập thông số màn hình">
                    </div>
                </div>

                {{-- Pin --}}
                <div class="spec-card">
                    <div class="spec-icon yellow">
                        <i class="fas fa-battery-full"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Pin</label>
                        <input type="text" name="pin" value="{{ $s->pin ?? '' }}" 
                               class="spec-input" placeholder="Nhập dung lượng pin">
                    </div>
                </div>

                {{-- Trọng lượng --}}
                <div class="spec-card">
                    <div class="spec-icon pink">
                        <i class="fas fa-weight"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Trọng Lượng</label>
                        <input type="text" name="trong_luong" value="{{ $s->trong_luong ?? '' }}" 
                               class="spec-input" placeholder="Nhập trọng lượng">
                    </div>
                </div>

                {{-- Hệ điều hành --}}
                <div class="spec-card">
                    <div class="spec-icon cyan">
                        <i class="fab fa-windows"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Hệ Điều Hành</label>
                        <input type="text" name="he_dieu_hanh" value="{{ $s->he_dieu_hanh ?? '' }}" 
                               class="spec-input" placeholder="Nhập hệ điều hành">
                    </div>
                </div>

                {{-- Cổng kết nối - Full width --}}
                <div class="spec-card full-width">
                    <div class="spec-icon red">
                        <i class="fas fa-plug"></i>
                    </div>
                    <div class="spec-content">
                        <label class="spec-label">Cổng Kết Nối</label>
                        <input type="text" name="cong_ket_noi" value="{{ $s->cong_ket_noi ?? '' }}" 
                               class="spec-input" placeholder="Nhập các cổng kết nối">
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="specs-footer">
            <button type="button" onclick="closeSpecsModal()" class="btn-cancel">
                <i class="fas fa-times-circle"></i>
                <span>Hủy Bỏ</span>
            </button>
            <button type="button" onclick="saveQuickSpecs('{{ $product->slug }}')" class="btn-save">
                <i class="fas fa-save"></i>
                <span>Lưu Thông Số</span>
            </button>
        </div>
    </form>
</div>

<style>
/* ===== OVERLAY ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(59, 130, 246, 0.2));
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* ===== CONTAINER ===== */
.modern-specs-container {
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    width: 100%;
    max-width: 1100px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.4s ease;
    position: relative;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== HEADER ===== */
.specs-header {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
    padding: 30px 40px;
    position: relative;
    border-bottom: 4px solid rgba(255, 255, 255, 0.2);
}

.close-button {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    font-size: 20px;
}

.close-button:hover {
    background: rgba(239, 68, 68, 0.9);
    border-color: #ef4444;
    transform: rotate(90deg) scale(1.1);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.laptop-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.header-text {
    flex: 1;
}

.header-title {
    font-size: 28px;
    font-weight: 800;
    color: white;
    margin: 0 0 8px 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    letter-spacing: 0.5px;
}

.product-name {
    font-size: 16px;
    color: rgba(255, 255, 255, 0.95);
    margin: 0;
    font-weight: 500;
}

/* ===== BODY ===== */
.specs-body {
    padding: 35px 40px;
    overflow-y: auto;
    flex: 1;
    background: linear-gradient(to bottom, #f8fafc, #ffffff);
}

/* Custom Scrollbar */
.specs-body::-webkit-scrollbar {
    width: 8px;
}

.specs-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.specs-body::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #3b82f6, #8b5cf6);
    border-radius: 10px;
}

.specs-body::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #2563eb, #7c3aed);
}

/* ===== SPECS GRID ===== */
.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

/* ===== SPEC CARD ===== */
.spec-card {
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.spec-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.15);
    transform: translateY(-2px);
}

.spec-card.full-width {
    grid-column: 1 / -1;
}

/* ===== SPEC ICON ===== */
.spec-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.spec-card:hover .spec-icon {
    transform: scale(1.1) rotate(5deg);
}

.spec-icon.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
.spec-icon.green { background: linear-gradient(135deg, #10b981, #059669); color: white; }
.spec-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; }
.spec-icon.orange { background: linear-gradient(135deg, #f59e0b, #ea580c); color: white; }
.spec-icon.indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; }
.spec-icon.yellow { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; }
.spec-icon.pink { background: linear-gradient(135deg, #ec4899, #db2777); color: white; }
.spec-icon.cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; }
.spec-icon.red { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }

/* ===== SPEC CONTENT ===== */
.spec-content {
    flex: 1;
}

.spec-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.spec-input {
    width: 100%;
    padding: 12px 14px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #1f2937;
    background: #f9fafb;
    transition: all 0.3s ease;
    outline: none;
}

.spec-input:focus {
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.spec-input::placeholder {
    color: #9ca3af;
    font-weight: 400;
}

/* ===== FOOTER ===== */
.specs-footer {
    padding: 25px 40px;
    background: #f9fafb;
    border-top: 2px solid #e5e7eb;
    display: flex;
    justify-content: center;
    gap: 16px;
}

.btn-cancel,
.btn-save {
    padding: 14px 32px;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-cancel {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
}

.btn-cancel:hover {
    background: linear-gradient(135deg, #4b5563, #374151);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.btn-save {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-save:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-cancel i,
.btn-save i {
    font-size: 18px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .modern-specs-container {
        border-radius: 0;
        max-height: 100vh;
        height: 100vh;
    }
    
    .specs-header {
        padding: 20px 24px;
    }
    
    .header-title {
        font-size: 22px;
    }
    
    .product-name {
        font-size: 14px;
    }
    
    .laptop-icon {
        width: 55px;
        height: 55px;
        font-size: 28px;
    }
    
    .specs-body {
        padding: 24px;
    }
    
    .specs-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .specs-footer {
        padding: 20px 24px;
        flex-direction: column;
    }
    
    .btn-cancel,
    .btn-save {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .close-button {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }
    
    .spec-icon {
        width: 42px;
        height: 42px;
        font-size: 18px;
    }
}
</style>