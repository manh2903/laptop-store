/**
 * PRODUCT DETAILS JAVASCRIPT MAIN FILE
 * Updated: Cập nhật tính năng Giỏ hàng, Check Login, Toast Notification
 */

/* ========================================================================== */
/* 1. WINDOW.ONLOAD: MODAL ĐỊA CHỈ & SHOWROOM SLIDER                          */
/* ========================================================================== */
window.onload = function () {
    var citis = document.getElementById("province");
    var districts = document.getElementById("district");
    var wards = document.getElementById("ward");
    var addressModal = document.getElementById('addressModal');
    var openBtn = document.getElementById('openModalBtn');
    var confirmBtn = document.getElementById('btnConfirm');
    var closeBtn = document.querySelector('.close-modal');
    var detailInput = document.getElementById("detailAddress");
    var errorMsg = document.getElementById("error-message");
    var noteText = document.querySelector(".note-text"); 
    var badgeNew = document.querySelector(".badge-new");

    if (noteText) noteText.style.display = "none";

    // Khởi tạo slider showroom
    initShowroomSlider();

    // Lấy dữ liệu tỉnh thành từ API
    var Parameter = {
        url: "https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json",
        method: "GET",
        responseType: "json",
    };

    if (typeof axios !== 'undefined') {
        axios(Parameter).then(function (result) {
            renderCity(result.data);
        });
    }

    function renderCity(data) {
        for (const x of data) {
            citis.options[citis.options.length] = new Option(x.Name, x.Id);
        }
        citis.onchange = function () {
            districts.length = 1; wards.length = 1;
            if (this.value != "") {
                if (noteText) noteText.style.display = "block";
                const result = data.filter(n => n.Id === this.value);
                for (const k of result[0].Districts) {
                    districts.options[districts.options.length] = new Option(k.Name, k.Id);
                }
            } else {
                if (noteText) noteText.style.display = "none";
            }
            checkValid();
        };
        districts.onchange = function () {
            wards.length = 1;
            const dataCity = data.filter((n) => n.Id === citis.value);
            if (this.value != "") {
                const dataWards = dataCity[0].Districts.filter(n => n.Id === this.value)[0].Wards;
                for (const w of dataWards) {
                    wards.options[wards.options.length] = new Option(w.Name, w.Id);
                }
            }
            checkValid();
        };
        wards.onchange = checkValid;
    }

    // Xử lý đóng mở Modal Địa chỉ
    if(openBtn) openBtn.onclick = () => { addressModal.style.display = "flex"; };
    if(closeBtn) closeBtn.onclick = () => { addressModal.style.display = "none"; };
    window.onclick = (e) => { if (e.target == addressModal) addressModal.style.display = "none"; };

    function checkValid() {
        const isFilled = citis.value && districts.value && wards.value && detailInput.value.trim().length > 0;
        if(confirmBtn) confirmBtn.disabled = !isFilled;
        if(isFilled) {
            if(errorMsg) errorMsg.classList.add("hidden-text");
        } else {
            if(errorMsg) errorMsg.classList.remove("hidden-text");
        }
    }
    if(detailInput) detailInput.addEventListener("input", checkValid);

    if(confirmBtn){
        confirmBtn.onclick = function () {
            const fullAddress = `${detailInput.value}, ${wards.options[wards.selectedIndex].text}, ${districts.options[districts.selectedIndex].text}, ${citis.options[citis.selectedIndex].text}`;
            const container = document.getElementById('shipping-address-container');
            if (badgeNew) badgeNew.style.display = "none";
            container.innerHTML = `
                <div class="address-display">
                    <span class="label-giao-den"><strong>GIAO ĐẾN:</strong></span> 
                    <span class="address-text">${fullAddress}</span>
                    <span class="change-address-btn" id="reOpenModalBtn">
                        <i class="ri-map-pin-line"></i> Đổi địa chỉ
                    </span>
                </div>
            `;
            document.getElementById('reOpenModalBtn').onclick = () => { addressModal.style.display = "flex"; };
            addressModal.style.display = "none";
        };
    }

    function initShowroomSlider() {
        const showroomData = [
            { name: "2004Store Gia Lâm", address: "Số 22 - Trâu Quỳ - Gia Lâm - HN", phone: "0999.999.999", stock: 3, map: "https://maps.app.goo.gl/Udy9KexTVaXu97yC9" },
            { name: "2004Store Cầu Giấy", address: "Số 15 - Cầu Giấy - Hà Nội", phone: "0988.888.888", stock: 1, map: "#" },
            { name: "2004Store Thanh Xuân", address: "Số 102 - Nguyễn Trãi - HN", phone: "0977.777.777", stock: 5, map: "#" },
            { name: "2004Store Đống Đa", address: "Số 55 - Tây Sơn - Hà Nội", phone: "0966.666.666", stock: 0, map: "#" },
            { name: "2004Store Hà Đông", address: "Số 88 - Quang Trung - HN", phone: "0955.555.555", stock: 2, map: "#" }
        ];

        const container = document.getElementById('showroom-list-container');
        const btnPrev = document.getElementById('prevShowroom');
        const btnNext = document.getElementById('nextShowroom');

        if (!container) return;

        container.innerHTML = showroomData.map((shop, index) => `
            <div class="showroom-item">
                <div class="store-name-row">
                    <div class="store-title">🏪 Store ${index + 1}: ${shop.name}</div>
                    <div class="stock-status">(Còn <strong style="color:#d70018">${shop.stock}</strong> sản phẩm)</div>
                </div>
                <div class="store-address">
                    <i class="ri-map-pin-2-fill"></i> <span>${shop.address}</span>
                </div>
                <div class="store-actions">
                    <a href="tel:${shop.phone}" class="btn-pill btn-phone">
                        <i class="ri-phone-fill"></i> ${shop.phone}
                    </a>
                    <a href="${shop.map}" target="_blank" class="btn-pill btn-map">
                        <i class="ri-navigation-line"></i> Chỉ đường
                    </a>
                </div>
            </div>
        `).join('');

        if(btnNext) btnNext.onclick = () => { container.scrollLeft += container.clientWidth; };
        if(btnPrev) btnPrev.onclick = () => { container.scrollLeft -= container.clientWidth; };

        container.onscroll = () => {
            if(btnPrev) btnPrev.style.display = container.scrollLeft <= 10 ? "none" : "flex";
            let maxScroll = container.scrollWidth - container.clientWidth;
            if(btnNext) btnNext.style.display = container.scrollLeft >= maxScroll - 10 ? "none" : "flex";
        };
        if(btnPrev) btnPrev.style.display = "none"; 
    }
};

/* ========================================================================== */
/* 2. GALLERY & LIGHTBOX (XỬ LÝ ẢNH, VIDEO, ZOOM)                             */
/* ========================================================================== */
document.addEventListener("DOMContentLoaded", function () {
    const imageContainer = document.getElementById("imageContainer");
    const videoContainer = document.getElementById("videoContainer");
    const featureBox = document.getElementById("specialFeatureBox");
    const mainImg = document.getElementById("mainImg");
    const mainVideo = document.getElementById("mainVideo");
    const playBtn = document.getElementById("customPlayBtn");
    const thumbContainer = document.getElementById("thumbContainer");
    const thumbItems = document.querySelectorAll(".thumb-item");
    const thumbPrevBtn = document.getElementById("thumbPrevBtn");
    const thumbNextBtn = document.getElementById("thumbNextBtn");
    const mainPrevBtn = document.getElementById("mainPrevBtn");
    const mainNextBtn = document.getElementById("mainNextBtn");
    const lightboxModal = document.getElementById("lightbox-modal");
    const lightboxImg = document.getElementById("lightbox-img");
    const lbClose = document.getElementById("lbClose");
    const lbPrev = document.getElementById("lbPrev");
    const lbNext = document.getElementById("lbNext");
    const lbCurrent = document.getElementById("lb-current");
    const lbTotal = document.getElementById("lb-total");
    const btnZoomIn = document.getElementById("btnZoomIn");
    const btnZoomOut = document.getElementById("btnZoomOut");
    const lbCanvas = document.querySelector(".lb-canvas");

    const ZOOM_BTN_MIN = 1.0; ZOOM_WHEEL_MIN = 1.5; ZOOM_DEFAULT = 1.5; MAX_ZOOM = 5.0; 
    let currentIndex = 0; let currentZoom = ZOOM_DEFAULT;
    let isDragging = false; let startX = 0, startY = 0;
    let translateX = 0, translateY = 0; let savedTranslateX = 0, savedTranslateY = 0;
    let isPlayClicked = false; 

    const imageThumbnails = Array.from(thumbItems).filter(item => {
        const type = item.getAttribute("data-type");
        return !type || type === 'image';
    });
    if(lbTotal) lbTotal.innerText = imageThumbnails.length;

    if (playBtn && mainVideo) {
        playBtn.addEventListener("click", () => {
            isPlayClicked = true; 
            playBtn.style.display = "none";
            mainVideo.style.opacity = 1;
            mainVideo.controls = true;
            mainVideo.play();
        });
        mainVideo.addEventListener("pause", () => { mainVideo.controls = true; });
        mainVideo.addEventListener("ended", () => { mainVideo.currentTime = 0; mainVideo.pause(); });
    }

    function updateMainDisplay(index) {
        if (index < 0) index = 0;
        if (index >= thumbItems.length) index = thumbItems.length - 1;
        currentIndex = index;

        if(imageContainer) imageContainer.style.display = 'none';
        if(videoContainer) videoContainer.style.display = 'none';
        if(featureBox) featureBox.style.display = 'none';
        
        if(mainVideo) { mainVideo.pause(); mainVideo.style.opacity = 0; mainVideo.controls = false; }
        
        const item = thumbItems[index];
        const type = item.getAttribute("data-type") || 'image';
        const src = item.getAttribute("data-src") || item.getAttribute("data-medium");

        if (type === 'video') {
            if(videoContainer) videoContainer.style.display = 'flex';
            if (isPlayClicked) {
                if(playBtn) playBtn.style.display = "none";
                if(mainVideo) { mainVideo.style.opacity = 1; mainVideo.controls = true; }
            } else {
                if(playBtn) playBtn.style.display = "flex";
                if(mainVideo) mainVideo.style.opacity = 0;
            }
        } else if (type === 'feature') {
            if(featureBox) featureBox.style.display = 'flex';
        } else {
            if(mainImg) { mainImg.src = src; if(imageContainer) imageContainer.style.display = 'flex'; }
        }

        document.querySelectorAll(".thumb-item.active").forEach(el => el.classList.remove("active"));
        item.classList.add("active");
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        checkNavButtons(index);
    }

    function checkNavButtons(index) {
        if(mainPrevBtn) mainPrevBtn.style.display = (index === 0) ? "none" : "flex";
        if(mainNextBtn) mainNextBtn.style.display = (index === thumbItems.length - 1) ? "none" : "flex";
    }

    thumbItems.forEach((item, index) => { item.addEventListener("click", () => updateMainDisplay(index)); });
    if(mainNextBtn) mainNextBtn.addEventListener("click", () => { if (currentIndex < thumbItems.length - 1) updateMainDisplay(currentIndex + 1); });
    if(mainPrevBtn) mainPrevBtn.addEventListener("click", () => { if (currentIndex > 0) updateMainDisplay(currentIndex - 1); });

    function updateLightboxImage(index) {
        const item = thumbItems[index];
        const largeSrc = item.getAttribute("data-large") || item.getAttribute("data-src");
        if(lightboxImg) lightboxImg.src = largeSrc;
        currentZoom = ZOOM_DEFAULT; translateX = 0; translateY = 0; savedTranslateX = 0; savedTranslateY = 0;
        updateTransform();
        let imgCount = 0;
        for(let i=0; i<=index; i++) {
            const t = thumbItems[i].getAttribute("data-type");
            if(!t || t === 'image') imgCount++;
        }
        if(lbCurrent) lbCurrent.innerText = imgCount;
        updateMainDisplay(index);
    }

    if(mainImg) {
        mainImg.addEventListener("click", () => {
            const type = thumbItems[currentIndex].getAttribute("data-type");
            if (type === 'video' || type === 'feature') return;
            if (lightboxModal) {
                updateLightboxImage(currentIndex);
                lightboxModal.classList.add("show");
                document.body.style.overflow = "hidden";
            }
        });
    }

    function closeLightbox() {
        if(lightboxModal) lightboxModal.classList.remove("show");
        document.body.style.overflow = "auto";
    }
    
    if(lbClose) lbClose.addEventListener("click", closeLightbox);

    function navigateLightbox(direction) {
        let newIndex = currentIndex; let loops = 0;
        do {
            newIndex += direction;
            if (newIndex >= thumbItems.length) newIndex = 0;
            if (newIndex < 0) newIndex = thumbItems.length - 1;
            loops++;
            const t = thumbItems[newIndex].getAttribute("data-type");
            if (!t || t === 'image') break;
        } while (loops < thumbItems.length);
        updateLightboxImage(newIndex);
    }

    if(lbNext) lbNext.addEventListener("click", (e) => { e.stopPropagation(); navigateLightbox(1); });
    if(lbPrev) lbPrev.addEventListener("click", (e) => { e.stopPropagation(); navigateLightbox(-1); });

    function updateTransform() {
        if(lightboxImg) {
            lightboxImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentZoom})`;
            if(lbCanvas) lbCanvas.style.cursor = currentZoom > 1.0 ? "grab" : "default";
        }
    }

    if(btnZoomOut) btnZoomOut.addEventListener("click", (e) => {
        e.stopPropagation();
        if (currentZoom > ZOOM_BTN_MIN) { 
            currentZoom -= 0.5;
            if(currentZoom < ZOOM_BTN_MIN) currentZoom = ZOOM_BTN_MIN;
            if(currentZoom === ZOOM_BTN_MIN) { translateX = 0; translateY = 0; savedTranslateX=0; savedTranslateY=0; }
            updateTransform();
        }
    });

    if(btnZoomIn) btnZoomIn.addEventListener("click", (e) => {
        e.stopPropagation();
        if (currentZoom < MAX_ZOOM) { currentZoom += 0.5; updateTransform(); }
    });

    if(lbCanvas) {
        lbCanvas.addEventListener("wheel", function(e) {
            e.preventDefault(); e.stopPropagation();
            if (e.deltaY < 0) { if (currentZoom < MAX_ZOOM) currentZoom += 0.2; } 
            else { if (currentZoom > ZOOM_WHEEL_MIN) currentZoom -= 0.2; }
            updateTransform();
        }, { passive: false });
    }

    if(lightboxImg) {
        lightboxImg.addEventListener("mousedown", (e) => {
            if(currentZoom > 1.0) { 
                isDragging = true; startX = e.clientX; startY = e.clientY;
                lightboxImg.style.cursor = "grabbing"; e.preventDefault();
            }
        });
        window.addEventListener("mouseup", () => {
            if (isDragging) {
                isDragging = false; lightboxImg.style.cursor = "grab";
                savedTranslateX = translateX; savedTranslateY = translateY;
            }
        });
        window.addEventListener("mousemove", (e) => {
            if (!isDragging) return; e.preventDefault();
            const deltaX = e.clientX - startX; const deltaY = e.clientY - startY;
            translateX = savedTranslateX + deltaX; translateY = savedTranslateY + deltaY;
            updateTransform();
        });
    }

    function checkThumbArrows() {
        const maxScroll = thumbContainer.scrollWidth - thumbContainer.clientWidth;
        if(thumbPrevBtn) thumbPrevBtn.classList.toggle("disabled", thumbContainer.scrollLeft <= 5);
        if(thumbNextBtn) thumbNextBtn.classList.toggle("disabled", maxScroll <= 0 || thumbContainer.scrollLeft >= maxScroll - 5);
    }
    if(thumbPrevBtn) thumbPrevBtn.addEventListener("click", () => thumbContainer.scrollBy({ left: -200, behavior: "smooth" }));
    if(thumbNextBtn) thumbNextBtn.addEventListener("click", () => thumbContainer.scrollBy({ left: 200, behavior: "smooth" }));
    if(thumbContainer) thumbContainer.addEventListener("scroll", checkThumbArrows);

    updateMainDisplay(0);
    setTimeout(checkThumbArrows, 500);
});

/* ========================================================================== */
/* 3. MODAL FULL SPECS (CHI TIẾT CẤU HÌNH)                                    */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('full-specs-modal');
    const openBtn = document.getElementById('openSpecsModalBtn');
    const closeBtn = document.getElementById('closeFullSpecsBtn');
    const modalBody = document.getElementById('modalBodyScroll');
    const tabs = document.querySelectorAll('.tab-item');
    const groups = document.querySelectorAll('.spec-group-block');

    if (!modal || !openBtn || !modalBody) return;

    function disableMainScroll() {
        const scrollWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = scrollWidth + 'px';
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }

    function enableMainScroll() {
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }

    openBtn.addEventListener('click', function(e) {
        e.preventDefault();
        modal.classList.add('active');
        disableMainScroll();
    });

    const closeModalFunc = () => {
        modal.classList.remove('active');
        enableMainScroll();
    };

    if (closeBtn) closeBtn.addEventListener('click', closeModalFunc);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModalFunc(); });

    let isManualScrolling = false;
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            isManualScrolling = true;
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.getAttribute('data-target');
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                const targetOffset = targetEl.offsetTop - modalBody.offsetTop;
                modalBody.scrollTo({ top: targetOffset - 5, behavior: 'smooth' });
            }
            setTimeout(() => { isManualScrolling = false; }, 800);
        });
    });

    modalBody.addEventListener('scroll', function() {
        if (isManualScrolling) return;
        let currentSectionId = "";
        groups.forEach(group => {
            const sectionTop = group.offsetTop - modalBody.offsetTop;
            if (modalBody.scrollTop >= sectionTop - 70) {
                currentSectionId = group.getAttribute('id');
            }
        });
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if (tab.getAttribute('data-target') === currentSectionId) tab.classList.add('active');
        });
    });
});

/* ========================================================================== */
/* 4. MODAL CẤU HÌNH & ORDER FLOW (MUA NGAY, NÂNG CẤP)                        */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    const configModal = document.getElementById('custom-config-modal');
    const openConfigBtn = document.querySelector('.option-custom');
    const closeConfigBtn = document.getElementById('closeCustomModal');
    const btnSubmit = document.getElementById('btn-submit-modal');
    
    const priceEl = document.getElementById('custom-total-price');
    const oldPriceEl = document.getElementById('old-price-display');
    const summaryTextEl = document.getElementById('summary-text');
    const extraInfoEl = document.getElementById('extra-info');
    const previewImg = document.getElementById('modal-preview-img');

    // Biến Token cho Ajax
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function openModalStandard(modalEl) {
        if(modalEl) {
            modalEl.classList.add('active');
            const scrollWidth = window.innerWidth - document.documentElement.clientWidth;
            document.body.style.paddingRight = scrollWidth + 'px';
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            updateModalState(); 
        }
    }

    function closeModalStandard(modalEl) {
        if(modalEl) {
            modalEl.classList.remove('active');
            document.body.style.paddingRight = '';
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        }
    }

    // --- HÀM TÍNH TOÁN GIÁ & TRẠNG THÁI ---
    function updateModalState() {
        let totalPrice = 0;
        let upgradeCost = 0; 
        let summaryParts = [];
        let currentColorImg = null;

        // 1. Lấy giá MÀU SẮC đang chọn
        const activeColor = document.querySelector('.color-sync-grid .option.active');
        if (activeColor) {
            const colorPrice = parseInt(activeColor.getAttribute('data-price')) || 0;
            const colorName = activeColor.getAttribute('data-name') || activeColor.getAttribute('data-color') || 'Mặc định';
            
            totalPrice += colorPrice;
            summaryParts.push(colorName);
            
            currentColorImg = activeColor.getAttribute('data-img');
            if (currentColorImg && previewImg) previewImg.src = currentColorImg;
        }

        // 2. Cộng giá các LINH KIỆN NÂNG CẤP
        const activeConfigs = document.querySelectorAll('.custom-section .config-card.active');
        activeConfigs.forEach(card => {
            const price = parseInt(card.getAttribute('data-price')) || 0;
            const name = card.getAttribute('data-name') || card.querySelector('strong').innerText;
            
            totalPrice += price;
            upgradeCost += price; 

            if (price > 0) summaryParts.push(name);
        });

        // 3. Cập nhật giao diện Giá & Text
        if (priceEl) priceEl.innerText = totalPrice.toLocaleString('vi-VN') + 'đ';

        if (summaryTextEl) {
            const productName = document.querySelector('h1') ? document.querySelector('h1').innerText : 'Sản phẩm';
            summaryTextEl.innerText = productName + ' - ' + summaryParts.join(' / ');
        }

        // 4. Logic Đổi tên nút Mua/Đặt
        if (btnSubmit) {
            if (upgradeCost > 0) {
                btnSubmit.innerText = 'ĐẶT NGAY (Tư vấn viên sẽ gọi lại)';
                btnSubmit.style.backgroundColor = '#d70018'; 
                if(extraInfoEl) extraInfoEl.style.display = 'block';
            } else {
                btnSubmit.innerText = 'XÁC NHẬN MUA NGAY';
                btnSubmit.style.backgroundColor = ''; 
                if(extraInfoEl) extraInfoEl.style.display = 'none';
            }
        }
        if (oldPriceEl) oldPriceEl.style.display = 'none';
    }

    // --- HÀM LẤY DỮ LIỆU ĐỂ GỬI ĐI ---
    function getSelectedData() {
        let totalPrice = 0;
        let selectedColorName = 'Mặc định';
        let upgrades = [];

        const activeColor = document.querySelector('.color-sync-grid .option.active');
        if (activeColor) {
            selectedColorName = activeColor.getAttribute('data-name') || activeColor.getAttribute('data-color') || 'Mặc định';
            totalPrice += parseInt(activeColor.getAttribute('data-price')) || 0;
        }

        document.querySelectorAll('.custom-section .config-card.active').forEach(card => {
            const price = parseInt(card.getAttribute('data-price')) || 0;
            totalPrice += price;
            if(price > 0) {
                upgrades.push(card.getAttribute('data-name') || card.querySelector('strong').innerText);
            }
        });

        const productName = document.querySelector('h1') ? document.querySelector('h1').innerText : "Sản phẩm";

        return {
            product_name: productName,
            color: selectedColorName,
            upgrades: upgrades,
            total_price: totalPrice.toLocaleString('vi-VN') + 'đ',
            raw_total: totalPrice 
        };
    }

    if (configModal && openConfigBtn) {
        const allOptions = document.querySelectorAll('.color-sync-grid .option, .config-card');
        allOptions.forEach(opt => {
            opt.addEventListener('click', function() {
                const siblings = this.parentElement.children;
                for (let sibling of siblings) sibling.classList.remove('active');
                this.classList.add('active');
                updateModalState();
            });
        });

        openConfigBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModalStandard(configModal);
        });

        if (closeConfigBtn) closeConfigBtn.addEventListener('click', () => closeModalStandard(configModal));
        configModal.addEventListener('click', (e) => { if(e.target === configModal) closeModalStandard(configModal); });

        if (btnSubmit) {
            btnSubmit.addEventListener('click', function(e) {
                e.preventDefault();
                const btnText = btnSubmit.innerText;
                const isOrderMode = btnText.includes('ĐẶT NGAY'); 
                handlePurchaseFlow(isOrderMode);
            });
        }
    }

    // --- HÀM XỬ LÝ MUA HÀNG CHÍNH ---
    function handlePurchaseFlow(isUpgraded) {
        const loginFlag = document.getElementById('login-flag');
        const isLoggedIn = loginFlag && loginFlag.value === '1';

        if (!isLoggedIn) {
            // --- CHƯA ĐĂNG NHẬP ---
            if (isUpgraded) {
                // Nếu nâng cấp -> Mở form Vãng lai (Order Modal)
                if(configModal) configModal.classList.remove('active');
                setTimeout(() => {
                    const orderModal = document.getElementById('ltf-order-modal');
                    if(orderModal) {
                        orderModal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                }, 300);
            } else {
                // Nếu mua thường -> Bắt đăng nhập
                if(configModal) configModal.classList.remove('active');
                
                if (typeof window.globalOpenLogin === 'function') {
                    window.globalOpenLogin(); 
                } else if (typeof window.openLoginModal === 'function') {
                    window.openLoginModal();
                } else {
                    alert("Vui lòng đăng nhập!");
                }
            }
        } else {
            // --- ĐÃ ĐĂNG NHẬP ---
            const data = getSelectedData(); 
            if (isUpgraded) {
                // Gửi mail báo giá
                if(configModal) configModal.classList.remove('active');
                const orderModal = document.getElementById('ltf-order-modal');
                if(orderModal) orderModal.classList.remove('active');
                document.body.style.overflow = '';
                sendEmailAjax(data);
            } else {
                // Chuyển sang Checkout (Mua ngay)
                window.location.href = `/checkout?product_name=${encodeURIComponent(data.product_name)}&color=${encodeURIComponent(data.color)}&price=${encodeURIComponent(data.total_price)}`;
            }
        }
    }

    // --- GẮN SỰ KIỆN CHO NÚT MUA NGAY TO BÊN NGOÀI ---
    const btnBuyNowMain = document.querySelector('.btn-buy-now');
    if (btnBuyNowMain) {
        const newBtnMain = btnBuyNowMain.cloneNode(true);
        btnBuyNowMain.parentNode.replaceChild(newBtnMain, btnBuyNowMain);
        newBtnMain.addEventListener('click', (e) => {
            e.preventDefault();
            handlePurchaseFlow(false); 
        });
    }

    // --- LOGIC FORM ORDER (Vãng lai) ---
    const orderModal = document.getElementById('ltf-order-modal');
    const orderForm = document.getElementById('ltf-order-form');
    if(orderForm && orderModal) {
        const btnExit = document.getElementById('ltf-btn-exit');
        const btnCloseX = document.getElementById('ltf-close-x');
        const closeOrder = () => closeModalStandard(orderModal);

        if(btnExit) btnExit.onclick = closeOrder;
        if(btnCloseX) btnCloseX.onclick = closeOrder;
        orderModal.onclick = (e) => { if(e.target === orderModal) closeOrder(); };
        
        const loadingOverlay = document.getElementById('ltf-loading-overlay');
        const toast = document.getElementById('ltf-success-toast');

        orderForm.onsubmit = function(e) {
            e.preventDefault();
            const name = document.getElementById('ltf-cust-name');
            const phone = document.getElementById('ltf-cust-phone');
            document.querySelectorAll('.error-border').forEach(el => el.classList.remove('error-border'));
            
            if(!name.value.trim() || !phone.value.trim()) {
                alert("Vui lòng nhập đầy đủ thông tin!");
                return;
            }
            
            closeOrder();
            if(loadingOverlay) loadingOverlay.classList.add('active');

            setTimeout(() => {
                if(loadingOverlay) loadingOverlay.classList.remove('active');
                if(toast) {
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 5000);
                }
                orderForm.reset();
            }, 1500);
        };
    }

    // --- GỬI MAIL AJAX ---
    function sendEmailAjax(data) {
        const loadingOverlay = document.getElementById('ltf-loading-overlay');
        const toast = document.getElementById('ltf-success-toast');
        if(loadingOverlay) loadingOverlay.classList.add('active');

        fetch('/api/send-upgrade-email', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(loadingOverlay) loadingOverlay.classList.remove('active');
            if (res.success || res.status === 'success') {
                if (toast) {
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 5000);
                }
            } else {
                alert("Lỗi: " + (res.message || "Vui lòng thử lại"));
            }
        })
        .catch(err => {
            if(loadingOverlay) loadingOverlay.classList.remove('active');
            console.error("Lỗi kết nối:", err);
        });
    }
});

/* ========================================================================== */
/* 5. TOGGLE DESCRIPTION & RELATED TABS & GLOBAL FUNCS                        */
/* ========================================================================== */
document.addEventListener("DOMContentLoaded", function() {
    const btnToggle = document.getElementById('btnToggleDesc');
    const wrapper = document.getElementById('descWrapper');
    const btnText = btnToggle ? btnToggle.querySelector('.btn-text') : null;
    
    if(btnToggle && wrapper) {
        btnToggle.addEventListener('click', function() {
            const isExpanded = wrapper.classList.contains('expanded');
            if (isExpanded) {
                wrapper.classList.remove('expanded');
                btnToggle.classList.remove('active');
                if(btnText) btnText.innerText = 'Xem thêm nội dung';
            } else {
                wrapper.classList.add('expanded');
                btnToggle.classList.add('active');
                if(btnText) btnText.innerText = 'Thu gọn nội dung';
            }
        });
    }
});

// Global functions
function openRelatedTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("related-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
        tablinks[i].style.color = "#666";
        tablinks[i].style.borderBottom = "2px solid transparent";
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
    evt.currentTarget.style.color = "#d70018";
    evt.currentTarget.style.borderBottom = "2px solid #d70018";
}

function scrollSlider(sliderId, direction) {
    var slider = document.getElementById(sliderId);
    if(slider) {
        var scrollAmount = slider.clientWidth; 
        if (direction === 1) slider.scrollLeft += scrollAmount;
        else slider.scrollLeft -= scrollAmount;
    }
}

/* ========================================================================== */
/* 6. REVIEW SYSTEM (ĐÁNH GIÁ & BỘ LỌC)                                       */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    window.toggleReviewForm = function() {
        const form = document.getElementById('write-review-form');
        if(form) {
            const isHidden = (window.getComputedStyle(form).display === 'none');
            form.style.display = isHidden ? 'block' : 'none';
            if (isHidden) form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingLabel = document.getElementById('rating-text-label');
    const ratingTexts = {
        5: 'Tuyệt vời - Cực kỳ hài lòng',
        4: 'Hài lòng - Sản phẩm tốt',
        3: 'Bình thường - Tạm được',
        2: 'Không hài lòng - Sản phẩm tệ',
        1: 'Rất tệ - Không nên mua'
    };

    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            if(ratingLabel) {
                ratingLabel.innerText = ratingTexts[this.value];
                ratingLabel.style.opacity = 0;
                setTimeout(() => ratingLabel.style.opacity = 1, 100);
            }
        });
    });

    window.previewImage = function(input) {
        const previewBox = document.getElementById('img-preview-box');
        const previewImg = document.getElementById('preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.style.display = 'inline-block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    window.removeImage = function() {
        document.getElementById('review-img-input').value = "";
        document.getElementById('img-preview-box').style.display = 'none';
    };

    let currentFilter = 'all';
    let visibleCount = 5;
    const increment = 5;

    window.filterReviews = function(filterType) {
        currentFilter = filterType;
        visibleCount = 5; 
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        if(event && event.target) event.target.closest('button').classList.add('active');
        renderReviewList();
    };

    window.loadMoreReviews = function() {
        visibleCount += increment;
        renderReviewList();
    };

    window.collapseReviews = function() {
        visibleCount = 5;
        renderReviewList();
        const filters = document.querySelector('.review-filters');
        if(filters) filters.scrollIntoView({ behavior: 'smooth' });
    };

    function renderReviewList() {
        const reviews = document.querySelectorAll('.item-review');
        let shown = 0;
        let totalMatches = 0;

        reviews.forEach(rv => {
            const star = rv.getAttribute('data-star');
            const hasImg = rv.getAttribute('data-has-img');
            let isMatch = false;

            if (currentFilter === 'all') isMatch = true;
            else if (currentFilter === 'has-img') isMatch = (hasImg === '1');
            else isMatch = (star === currentFilter);

            if (isMatch) {
                totalMatches++;
                if (shown < visibleCount) {
                    rv.style.display = 'flex';
                    shown++;
                } else {
                    rv.style.display = 'none';
                }
            } else {
                rv.style.display = 'none';
            }
        });

        const btnLoadMore = document.getElementById('btn-load-more');
        const btnCollapse = document.getElementById('btn-collapse');

        if(btnLoadMore) {
            if (totalMatches > shown) {
                btnLoadMore.style.display = 'inline-block';
                btnLoadMore.innerText = `Xem thêm ${totalMatches - shown} đánh giá`;
                if(btnCollapse) btnCollapse.style.display = 'none';
            } else {
                btnLoadMore.style.display = 'none';
                if(btnCollapse) {
                    btnCollapse.style.display = (totalMatches > 5) ? 'inline-block' : 'none';
                }
            }
        }
    }
    renderReviewList();
});

/* ========================================================================== */
/* 7. XỬ LÝ NÚT MUA NGAY ĐỒNG BỘ                                               */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả nút có class mua ngay hoặc id của nút sticky
    const buyNowBtns = document.querySelectorAll('.btn-buy-now, #stickyBtnBuy');
    const loginFlag = document.getElementById('login-flag');

    buyNowBtns.forEach(btn => {
        // KHÔNG DÙNG cloneNode ở đây để tránh mất các logic khác
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const isLoggedIn = loginFlag && loginFlag.value === '1';

            // 1. Kiểm tra đăng nhập
            if (!isLoggedIn) {
                if (typeof window.globalOpenLogin === 'function') {
                    window.globalOpenLogin(); 
                } else {
                    alert("Vui lòng đăng nhập để mua hàng!");
                }
                return;
            }

            // 2. Xác định form và submit
            // Ưu tiên form chính, nếu không thấy thì dùng form sticky
            const mainForm = document.getElementById('formBuyNowMain');
            const stickyForm = document.getElementById('formBuyNowSticky');
            
            // Cập nhật số lượng trước khi gửi (nếu bạn có ô chọn số lượng)
            const currentQty = document.getElementById('buy_now_qty')?.value || 1;

            if (mainForm) {
                const qtyHidden = mainForm.querySelector('.buy-now-qty');
                if (qtyHidden) qtyHidden.value = currentQty;
                mainForm.submit();
            } else if (stickyForm) {
                const qtyHidden = stickyForm.querySelector('.buy-now-qty');
                if (qtyHidden) qtyHidden.value = currentQty;
                stickyForm.submit();
            }
        });
    });
});

/* ========================================================================== */
/* 8. ĐỒNG BỘ ACTIVE & STICKY BAR (MÀU SẮC, GIÁ)                              */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    const versionOptions = document.querySelectorAll('.version-group .option');
    const colorOptions = document.querySelectorAll('.color-group .option');
    
    const stickyVariant = document.getElementById('sticky-variant-txt');
    const stickyImg = document.getElementById('sticky-img');
    const stickyPriceMain = document.getElementById('sticky-price-main');
    const stickyPriceOld = document.getElementById('sticky-price-old');

    const studentPriceBox = document.querySelector('.price-box-right .price-main');
    const mainOldPriceBox = document.querySelector('.price-box-right .price-old');

    function updateProductState() {
        const activeVersion = document.querySelector('.version-group .option.active');
        const activeColor = document.querySelector('.color-group .option.active');

        if (!activeVersion || !activeColor) return;

        const versionName = activeVersion.getAttribute('data-name');
        const colorName = activeColor.getAttribute('data-name');
        const imgSrc = activeColor.getAttribute('data-img');
        
        let rawPrice = activeColor.getAttribute('data-price');
        let rawOldPrice = activeColor.getAttribute('data-old-price');

        const originalPrice = parseInt(rawPrice.toString().replace(/\D/g, '')) || 0;
        const oldPrice = parseInt(rawOldPrice.toString().replace(/\D/g, '')) || 0;
        
        const studentPrice = originalPrice - 500000; 

        const strOriginalPrice = originalPrice.toLocaleString('vi-VN') + 'đ';
        const strStudentPrice = studentPrice.toLocaleString('vi-VN') + 'đ';
        const strOldPrice = oldPrice.toLocaleString('vi-VN') + 'đ';

        if (stickyVariant) stickyVariant.innerText = `${versionName} - ${colorName}`;
        if (stickyImg && imgSrc) stickyImg.src = imgSrc;
        if (stickyPriceMain) stickyPriceMain.innerText = strOriginalPrice; 
        if (stickyPriceOld) stickyPriceOld.innerText = strOldPrice;

        if (studentPriceBox) studentPriceBox.innerText = strStudentPrice; 
        if (mainOldPriceBox) mainOldPriceBox.innerText = strOldPrice;
    }

    function handleOptionClick(options, clickedItem) {
        options.forEach(opt => opt.classList.remove('active'));
        clickedItem.classList.add('active');
        updateProductState();
    }

    versionOptions.forEach(opt => {
        opt.addEventListener('click', function() { handleOptionClick(versionOptions, this); });
    });

    colorOptions.forEach(opt => {
        opt.addEventListener('click', function() { handleOptionClick(colorOptions, this); });
    });

    updateProductState();
});

/* ========================================================================== */
/* 9. LOGIC STICKY BAR: ẨN/HIỆN KHI CUỘN                                      */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    const stickyBar = document.getElementById('sticky-compact-bar');
    let isScrolling;

    if (stickyBar) {
        window.addEventListener('scroll', function() {
            stickyBar.classList.add('is-hidden');
            window.clearTimeout(isScrolling);
            isScrolling = setTimeout(function() {
                stickyBar.classList.remove('is-hidden');
            }, 300); 
        });
    }
});

/* ========================================================================== */
/* 10. ADD TO CART LOGIC (ĐỒNG BỘ CẢ 2 NÚT)                                   */
/* ========================================================================== */
document.addEventListener('DOMContentLoaded', function() {
    const cartButtons = document.querySelectorAll('#addToCartBtn, #stickyBtnCart');
    const loginFlag = document.getElementById('login-flag');
    
    cartButtons.forEach(btn => {
        btn.addEventListener('click', function(e) { // Dùng addEventListener thay vì onclick
            e.preventDefault();
            
            const isLoggedIn = loginFlag && loginFlag.value === '1';

            if (!isLoggedIn) {
                if(typeof window.globalOpenLogin === 'function') window.globalOpenLogin(); 
                else alert("Vui lòng đăng nhập!");
                return; 
            }

            const productId = this.getAttribute('data-id'); 
            const originalHTML = this.innerHTML;
            
            this.innerHTML = '<i class="ri-loader-4-line ri-spin"></i>';
            this.style.pointerEvents = 'none';

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            axios.post("/gio-hang/them", { product_id: productId }, {
                headers: { 'X-CSRF-TOKEN': token }
            })
            .then(res => {
                this.innerHTML = originalHTML;
                this.style.pointerEvents = 'auto';

                if (res.data.status === 'success') {
                    // Cập nhật tất cả Badge trên Header (PC + Mobile)
                    document.querySelectorAll('#cart-badge-pc, .cart-badge-mobile').forEach(badge => {
                        badge.innerText = res.data.total_count;
                    });
                    
                    if(typeof window.showToast === 'function') {
                        window.showToast("Thành công", res.data.message, "success");
                    }
                }
            })
            .catch(err => {
                this.innerHTML = originalHTML;
                this.style.pointerEvents = 'auto';
                if(typeof window.showToast === 'function') {
                    window.showToast("Lỗi", "Không thể thêm vào giỏ hàng", "error");
                }
            });
        });
    });
});