// ========================================
// STICKY HEADER & MENU (Dính cả 2 thanh)
// ========================================
window.addEventListener("scroll", function() {
    var header = document.querySelector("header");
    var mainMenu = document.querySelector(".main-menu");
    
    // Nếu cuộn quá 100px
    if (window.scrollY > 100) {
        header.classList.add("sticky");
        mainMenu.classList.add("sticky");
        
        // Thêm padding cho body để bù đắp khoảng trống bị mất
        // (110px Header cũ + 50px Menu cũ = 160px)
        document.body.style.paddingTop = "160px"; 
    } else {
        header.classList.remove("sticky");
        mainMenu.classList.remove("sticky");
        
        // Trả lại trạng thái ban đầu
        document.body.style.paddingTop = "0";
    }
});

/* ================================================================ */
/* GLOBAL LOGIN MODAL (CODE CHUẨN - MASTER QUẢN LÝ) */
/* ================================================================ */

// 1. Hàm tính độ rộng thanh cuộn (Chống giật màn hình)
function getScrollbarWidth() {
    return window.innerWidth - document.documentElement.clientWidth;
}

// 2. Khai báo hàm MỞ (Gán vào window để trang con gọi được)
window.globalOpenLogin = function() {
    const loginModal = document.getElementById('login-modal');
    if (loginModal) {
        loginModal.classList.add('open'); // Thêm class hiện
        loginModal.style.display = 'flex'; // Đảm bảo display flex
        
        // KHÓA CUỘN CỨNG + BÙ PADDING
        const scrollWidth = getScrollbarWidth();
        document.body.style.paddingRight = scrollWidth + 'px';
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    } else {
        console.error("Lỗi: Không tìm thấy Modal Login (ID='login-modal') ở Master Layout");
    }
};

// 3. Khai báo hàm ĐÓNG
window.globalCloseLogin = function() {
    const loginModal = document.getElementById('login-modal');
    if (loginModal) {
        loginModal.classList.remove('open');
        loginModal.style.display = 'none';
        
        // MỞ KHÓA CUỘN
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        // Xóa class cũ nếu có
        document.body.classList.remove('no-scroll');
        document.documentElement.classList.remove('no-scroll');
    }
};

// 4. Tự động chạy khi tải trang (Cho nút Header và nút X)
document.addEventListener('DOMContentLoaded', function() {
    // Nút ở Header (class .login-btn)
    const headerLoginBtn = document.querySelector('.login-btn');
    if (headerLoginBtn) {
        headerLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.globalOpenLogin();
        });
    }

    // Nút Đóng (X)
    const closeLogin = document.querySelector('.close-login');
    if (closeLogin) {
        closeLogin.addEventListener('click', window.globalCloseLogin);
    }

    // Click ra ngoài (Overlay)
    const loginModal = document.getElementById('login-modal');
    if(loginModal){
        window.addEventListener('click', function(e) {
            if (e.target === loginModal) {
                window.globalCloseLogin();
            }
        });
    }
});
// ========================================
// SLIDER BANNER TỰ ĐỘNG (slides-inner) - BẢN FIX LỖI TRẮNG TRANG
// ========================================
window.addEventListener('load', function () {
    const track = document.querySelector('.slides-inner');
    const mainSlider = document.querySelector('.main-slider'); // Lấy khung bao ngoài
    let slides = document.querySelectorAll('.slide');

    // Nếu không tìm thấy slide nào thì dừng lại
    if (!track || !mainSlider || slides.length === 0) return;

    // Lấy chiều rộng chính xác
    let slideWidth = mainSlider.clientWidth;

    // --- TẠO HIỆU ỨNG LẶP VÔ TẬN (INFINITE LOOP) ---
    const firstClone = slides[0].cloneNode(true);
    const lastClone = slides[slides.length - 1].cloneNode(true);
    
    firstClone.id = 'first-clone';
    lastClone.id = 'last-clone';

    track.appendChild(firstClone);
    track.prepend(lastClone);

    // Cập nhật lại danh sách slides
    slides = document.querySelectorAll('.slide');

    // Khởi tạo biến
    let index = 1; 
    let isDragging = false; 
    let startPos = 0; 
    let currentTranslate = 0; 
    let prevTranslate = 0; 
    let animationID; 
    let autoPlayTimer; 

    // Đặt vị trí ban đầu
    track.style.transform = `translateX(${-slideWidth * index}px)`;

    // === [QUAN TRỌNG] Tự động dừng khi người dùng chuyển Tab (Fix lỗi trắng trang) ===
    document.addEventListener("visibilitychange", function() {
        if (document.hidden) {
            stopAutoPlay(); // Tab ẩn -> Dừng ngay
        } else {
            startAutoPlay(); // Tab hiện -> Chạy tiếp
        }
    });

    // === TỰ ĐỘNG CHẠY ===
    function startAutoPlay() {
        clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(() => {
            index++;
            
            // [BẢO VỆ] Nếu index vượt quá giới hạn (do lỗi trình duyệt), reset cứng về 1
            if (index >= slides.length) {
                index = 1;
                track.style.transition = 'none';
                track.style.transform = `translateX(${-slideWidth * index}px)`;
                return; 
            }

            moveToIndex(true); 
        }, 4000);
    }

    function stopAutoPlay() {
        clearInterval(autoPlayTimer);
    }

    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    // Hàm di chuyển slide
    function moveToIndex(withTransition = true) {
        currentTranslate = -slideWidth * index;
        prevTranslate = currentTranslate;

        track.style.transition = withTransition ? 'transform 0.5s ease-out' : 'none';
        track.style.transform = `translateX(${currentTranslate}px)`;

        updateDots();
    }

    // === XỬ LÝ SỰ KIỆN KÉO THẢ ===
    slides.forEach(slide => {
        slide.addEventListener('dragstart', e => e.preventDefault());
        
        // Touch events
        slide.addEventListener('touchstart', handleStart);
        slide.addEventListener('touchmove', handleMove);
        slide.addEventListener('touchend', handleEnd);

        // Mouse events
        slide.addEventListener('mousedown', handleStart);
        slide.addEventListener('mousemove', handleMove);
        slide.addEventListener('mouseup', handleEnd);
        slide.addEventListener('mouseleave', () => { if (isDragging) handleEnd(); });
    });

    function handleStart(e) {
        isDragging = true;
        stopAutoPlay(); 
        startPos = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
        track.style.transition = 'none'; 
        track.style.cursor = 'grabbing'; 
        animationID = requestAnimationFrame(animation);
    }

    function handleMove(e) {
        if (!isDragging) return;
        const currentPos = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
        currentTranslate = prevTranslate + currentPos - startPos;
    }

    function handleEnd() {
        if (!isDragging) return;
        isDragging = false;
        cancelAnimationFrame(animationID);
        track.style.cursor = 'grab'; 

        const movedBy = currentTranslate - prevTranslate;

        // Logic chuyển slide khi kéo
        if (movedBy < -70 && index < slides.length - 1) index++; 
        if (movedBy > 70 && index > 0) index--; 

        moveToIndex(true);
        resetAutoPlay(); 
    }

    function animation() {
        track.style.transform = `translateX(${currentTranslate}px)`;
        if (isDragging) requestAnimationFrame(animation);
    }

    // === XỬ LÝ VÒNG LẶP (Transition End) ===
    track.addEventListener('transitionend', () => {
        // Kiểm tra an toàn: Nếu slide không tồn tại (lỗi hiếm gặp), thoát
        if (!slides[index]) return;

        if (slides[index].id === 'first-clone') {
            index = 1;
            moveToIndex(false);
        }
        if (slides[index].id === 'last-clone') {
            index = slides.length - 2;
            moveToIndex(false);
        }
    });

    // === DOTS ===
    const dots = document.querySelectorAll('.dot');
    function updateDots() {
        dots.forEach(d => d.classList.remove('active'));
        // Tính toán index thật để active dot
        let realIndex = index - 1;
        if (index === 0) realIndex = dots.length - 1;
        if (index === slides.length - 1) realIndex = 0;
        
        // Bảo vệ: Chỉ add class nếu dot tồn tại
        if (dots[realIndex]) dots[realIndex].classList.add('active');
    }

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            index = i + 1; 
            moveToIndex(true);
            resetAutoPlay();
        });
    });

    // === RESPONSIVE ===
    window.addEventListener('resize', () => {
        slideWidth = mainSlider.clientWidth; // Cập nhật lại width chuẩn
        track.style.transition = 'none';
        currentTranslate = -slideWidth * index;
        prevTranslate = currentTranslate;
        track.style.transform = `translateX(${currentTranslate}px)`;
    });

    // Start
    updateDots();
    startAutoPlay();
});

// ========================================
// CHUYỂN ĐỔI SẢN PHẨM (Laptop Promo) - CHUYỂN 1 SẢN PHẨM/LẦN
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // 1. Lấy các thành phần cần thiết
    const productList = document.querySelector('#laptop-promo-section .product-list');
    const prevBtn = document.querySelector('#laptop-promo-section .nav-btn.prev');
    const nextBtn = document.querySelector('#laptop-promo-section .nav-btn.next');
    const productCards = document.querySelectorAll('#laptop-promo-section .product-card');

    if (!productList || productCards.length === 0 || !prevBtn || !nextBtn) {
        console.error("Thiếu các thành phần HTML cần thiết cho carousel.");
        return;
    }

    // --- Cấu hình và Biến Trạng thái ---
    let currentScrollPosition = 0; // Vị trí cuộn hiện tại (tính bằng pixel)
    const cardWidth = 251; // Chiều rộng cố định của thẻ sản phẩm (theo CSS)
    const gap = 16; // Khoảng cách giữa các thẻ (theo CSS)
    const scrollAmount = cardWidth + gap; // Độ dịch chuyển cho mỗi lần click
    
    // Chiều rộng khung chứa (giả định 1280 - 40 padding = 1240px cho nội dung)
    const containerWidth = 1240; 
    
    // Tổng chiều rộng danh sách
    const totalProductWidth = (productCards.length * cardWidth) + ((productCards.length - 1) * gap);
    
    // Giới hạn cuộn cuối cùng
    const scrollLimit = totalProductWidth - containerWidth;
    const maxScroll = Math.max(0, scrollLimit);

    // Biến cho chức năng Kéo thả (Drag)
    let isDragging = false;
    let startX; // Vị trí chuột khi bắt đầu kéo
    let initialScrollPosition; // Vị trí cuộn tại thời điểm bắt đầu kéo

    /**
     * Cập nhật vị trí cuộn
     * @param {number} position - Vị trí cuộn mới (tính bằng pixel)
     * @param {boolean} useTransition - Có sử dụng hiệu ứng chuyển đổi CSS (transition) hay không
     */
    function setScrollPosition(position, useTransition = true) {
        // Đảm bảo vị trí nằm trong giới hạn [0, maxScroll]
        currentScrollPosition = Math.max(0, Math.min(position, maxScroll));
        
        // Áp dụng độ dịch chuyển bằng CSS transform
        productList.style.transform = `translateX(-${currentScrollPosition}px)`;
        productList.style.transition = useTransition ? 'transform 0.3s ease-out' : 'none';
    }

    /**
     * Cập nhật trạng thái vô hiệu hóa của nút Prev và Next
     */
    function updateNavButtons() {
        // Nút Prev (trái)
        if (currentScrollPosition <= 1) { // Dùng ngưỡng nhỏ (1px)
            prevBtn.disabled = true;
            prevBtn.style.opacity = '0.5';
            prevBtn.style.cursor = 'default';
        } else {
            prevBtn.disabled = false;
            prevBtn.style.opacity = '1';
            prevBtn.style.cursor = 'pointer';
        }

        // Nút Next (phải)
        if (currentScrollPosition >= maxScroll - 1) { // Dùng ngưỡng nhỏ (1px)
            nextBtn.disabled = true;
            nextBtn.style.opacity = '0.5';
            nextBtn.style.cursor = 'default';
        } else {
            nextBtn.disabled = false;
            nextBtn.style.opacity = '1';
            nextBtn.style.cursor = 'pointer';
        }
    }

    /**
     * Thực hiện cuộn danh sách sản phẩm theo hướng
     * @param {string} direction - 'next' hoặc 'prev'
     */
    function scrollProducts(direction) {
        let newPosition;
        if (direction === 'next') {
            newPosition = currentScrollPosition + scrollAmount;
        } else if (direction === 'prev') {
            newPosition = currentScrollPosition - scrollAmount;
        }
        
        setScrollPosition(newPosition);
        updateNavButtons();
    }
    
    // --- Xử lý Kéo thả bằng Chuột (Drag) ---

    // Bắt đầu kéo
    productList.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        initialScrollPosition = currentScrollPosition;     
        
        // Ngăn chặn việc chọn văn bản khi kéo
        productList.style.userSelect = 'none'; 
        
        // Tắt transition trong quá trình kéo để cuộn tức thời
        productList.style.transition = 'none'; 
    });

    // Trong khi kéo
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        
        // Tính toán sự dịch chuyển của chuột
        const walk = e.pageX - startX; 
        
        // Vị trí cuộn mới = Vị trí ban đầu - Độ dịch chuyển chuột
        // Dấu '-' vì kéo chuột sang phải (walk dương) làm cuộn sang trái (giảm position)
        let newPosition = initialScrollPosition - walk;
        
        // Đảm bảo không cuộn quá giới hạn (không dùng transition)
        setScrollPosition(newPosition, false);
    });

    // Kết thúc kéo
    document.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;

        // Bật lại transition
        productList.style.transition = 'transform 0.3s ease-out';
        productList.style.userSelect = 'auto'; 

        // Cập nhật trạng thái nút
        updateNavButtons();
        
        // *** TÙY CHỌN: Chức năng 'snap' (cuộn đến thẻ gần nhất) ***
        // Nếu muốn thêm chức năng 'snap' sau khi kéo:
        // const snappedPosition = Math.round(currentScrollPosition / scrollAmount) * scrollAmount;
        // setScrollPosition(snappedPosition);
        // updateNavButtons();
    });

    // Ngăn chặn hành vi kéo mặc định của trình duyệt
    productList.addEventListener('mouseleave', () => {
        if (isDragging) {
            // Xử lý khi chuột rời khỏi vùng kéo nhưng vẫn đang ở trạng thái kéo
            // Giống như mouseup, nhưng không cần cập nhật snap nếu không dùng
            isDragging = false;
            productList.style.userSelect = 'auto'; 
            updateNavButtons();
        }
    });

    // --- Event Listeners cho các nút điều hướng ---
    nextBtn.addEventListener('click', function() {
        if (!nextBtn.disabled) {
            scrollProducts('next');
        }
    });

    prevBtn.addEventListener('click', function() {
        if (!prevBtn.disabled) {
            scrollProducts('prev');
        }
    });

    // 3. Khởi tạo trạng thái ban đầu (vô hiệu hóa nút Prev)
    updateNavButtons();
});


// ========================================
// LỌC VÀ SẮP XẾP SẢN PHẨM (SORT BUTTONS)
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử cần thiết
    const sortButtons = document.querySelectorAll('.sort-btn');
    const productGrid = document.querySelector('.full-product-grid-new');
    const productCards = Array.from(document.querySelectorAll('.product-card.full-list-card'));

    if (!productGrid || productCards.length === 0 || sortButtons.length === 0) {
        console.error("Thiếu các thành phần HTML cho chức năng lọc/sắp xếp.");
        return;
    }

    // Lưu dữ liệu sản phẩm gốc
    const originalProducts = productCards.map(card => {
        // Lấy giá hiện tại (bỏ dấu chấm và chữ "đ")
        const currentPriceText = card.querySelector('.current-price').textContent.trim();
        const currentPrice = parseInt(currentPriceText.replace(/\D/g, ''));
        
        // Lấy giá cũ (nếu có)
        const oldPriceEl = card.querySelector('.old-price');
        let oldPrice = currentPrice;
        if (oldPriceEl) {
            const oldPriceText = oldPriceEl.textContent.trim();
            oldPrice = parseInt(oldPriceText.replace(/\D/g, ''));
        }
        
        // Tính % giảm giá
        let discountPercent = 0;
        if (oldPrice > currentPrice) {
            discountPercent = Math.round(((oldPrice - currentPrice) / oldPrice) * 100);
        }
        
        return {
            element: card,
            currentPrice: currentPrice,
            oldPrice: oldPrice,
            discountPercent: discountPercent
        };
    });

    /**
     * Hiển thị danh sách sản phẩm
     * @param {Array} products - Mảng đối tượng sản phẩm cần hiển thị
     */
    function displayProducts(products) {
        // Xóa tất cả sản phẩm hiện tại
        productGrid.innerHTML = '';
        
        // Thêm lại các sản phẩm theo thứ tự mới
        products.forEach(product => {
            productGrid.appendChild(product.element);
        });
    }

    /**
     * Lọc sản phẩm theo % khuyến mãi VÀ sắp xếp từ cao xuống thấp
     * @param {number} minDiscount - % khuyến mãi tối thiểu
     */
    function filterByDiscount(minDiscount) {
        // Lọc sản phẩm có % giảm >= minDiscount
        const filtered = originalProducts.filter(p => p.discountPercent >= minDiscount);
        
        // Sắp xếp theo % giảm giá từ CAO xuống THẤP
        filtered.sort((a, b) => b.discountPercent - a.discountPercent);
        
        displayProducts(filtered);
    }

    /**
     * Sắp xếp sản phẩm theo giá
     * @param {string} order - 'asc' (thấp -> cao) hoặc 'desc' (cao -> thấp)
     */
    function sortByPrice(order) {
        const sorted = [...originalProducts].sort((a, b) => {
            if (order === 'asc') {
                return a.currentPrice - b.currentPrice; // Giá thấp -> cao
            } else {
                return b.currentPrice - a.currentPrice; // Giá cao -> thấp
            }
        });
        displayProducts(sorted);
    }

    /**
     * Hiển thị tất cả sản phẩm (mặc định)
     */
    function showAllProducts() {
        displayProducts(originalProducts);
    }

    /**
     * Cập nhật trạng thái active cho nút
     * @param {HTMLElement} activeButton - Nút được click
     */
    function updateActiveButton(activeButton) {
        // Xóa style active của tất cả nút
        sortButtons.forEach(btn => {
            btn.style.backgroundColor = '#f3f3f3';
            btn.style.color = '#555555';
            btn.style.fontWeight = '400';
        });
        
        // Thêm style active cho nút được chọn
        activeButton.style.backgroundColor = '#c8102e';
        activeButton.style.color = 'white';
        activeButton.style.fontWeight = '600';
    }

    // Gắn sự kiện click cho từng nút
    sortButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const buttonText = this.textContent.trim();
            
            // Cập nhật trạng thái active
            updateActiveButton(this);
            
            // Xử lý theo loại nút
            if (buttonText.includes('Phổ biến')) {
                // Hiển thị tất cả sản phẩm theo thứ tự gốc
                showAllProducts();
                
            } else if (buttonText.includes('Khuyến mãi HOT')) {
                // Lọc sản phẩm giảm >= 20% VÀ sắp xếp theo % giảm từ cao -> thấp
                filterByDiscount(20);
                
            } else if (buttonText.includes('Giá Thấp - Cao')) {
                // Sắp xếp giá tăng dần
                sortByPrice('asc');
                
            } else if (buttonText.includes('Giá Cao - Thấp')) {
                // Sắp xếp giá giảm dần
                sortByPrice('desc');
            }
        });
    });

    // Đặt nút "Phổ biến" làm active mặc định
    if (sortButtons.length > 0) {
        updateActiveButton(sortButtons[0]);
    }
});


// Hàm thêm vào giỏ hàng (Dùng chung cho cả 2 trang)
function addToCart(productId) {
    // 1. Lấy token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        alert("Lỗi bảo mật: Không tìm thấy CSRF Token!");
        return;
    }

    // 2. Xác định số lượng
    // Nếu trang web có ô nhập số lượng (thường ở trang chi tiết), lấy giá trị đó.
    // Nếu không tìm thấy ô nhập (trang chủ), mặc định là 1.
    const qtyInput = document.getElementById('quantity-input'); 
    let quantity = 1;
    
    if (qtyInput) {
        quantity = parseInt(qtyInput.value);
    }

    // 3. Hiệu ứng Loading (Optional - cho chuyên nghiệp)
    // Nếu bạn có overlay loading thì bật nó lên
    const loadingOverlay = document.getElementById('ltf-loading-overlay');
    if(loadingOverlay) loadingOverlay.classList.add('active');

    // 4. Gọi API
    fetch('/api/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        // Tắt loading
        if(loadingOverlay) loadingOverlay.classList.remove('active');

        if (data.status === 401) {
            // Chưa đăng nhập -> Hiện modal login
            // Giả sử modal id="login-modal"
            const loginModal = document.getElementById('login-modal');
            if(loginModal) {
                loginModal.style.display = 'flex';
            } else {
                alert("Vui lòng đăng nhập để mua hàng!");
                window.location.href = '/login'; // Hoặc chuyển trang login
            }
        } else if (data.status === 200) {
            // Thành công
            alert("✅ " + data.message);
            
            // Cập nhật số trên icon giỏ hàng
            updateCartCount(data.total_items);
        } else {
            alert("❌ Có lỗi: " + data.message);
        }
    })
    .catch(error => {
        if(loadingOverlay) loadingOverlay.classList.remove('active');
        console.error('Error:', error);
    });
}

// Hàm phụ để cập nhật số lượng trên icon Header
function updateCartCount(count) {
    // Tìm tất cả các chỗ hiển thị số lượng (PC + Mobile)
    const badges = document.querySelectorAll('.cart-count, .badge-cart, #lblCartCount'); 
    badges.forEach(el => {
        el.innerText = count;
        el.style.display = count > 0 ? 'block' : 'none'; // Ẩn nếu = 0
    });
}

// Hàm Mua ngay (Thêm xong chuyển trang)
function buyNow(productId) {
    // Gọi hàm thêm, nhưng sửa lại logic một chút hoặc gọi API xong thì redirect
    // Cách đơn giản nhất:
    addToCart(productId);
    setTimeout(() => {
        window.location.href = '/gio-hang';
    }, 1000); // Chờ 1s cho api chạy xong rồi chuyển
}