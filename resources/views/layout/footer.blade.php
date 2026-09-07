<footer>
    <div class="footer-top-keywords">
        <div class="container">
            <h3>Sản phẩm được tìm kiếm nhiều nhất</h3>
            <div class="keyword-list">
                <a href="#">Asus TUF Gaming</a>
                <a href="#">Lenovo Ideapad Gaming</a>
                <a href="#">MacBook Air M2</a>
                <a href="#">MacBook Air 13 inch</a>
                <a href="#">MSI Gaming</a>
                <a href="#">Lenovo LOQ Gaming</a>
                <a href="#">MacBook Air 15 inch</a>
                <a href="#">HP Gaming</a>
                <a href="#">MacBook Air 13 inch M2 10GPU</a>
                <a href="#">MacBook Air 15 inch M2 Sạc 35W</a>
                <a href="#">Lenovo LOQ Gaming 15IAX9 i5 12450HX</a>
                <a href="#">Asus Vivobook 14 Oled</a>
                <a href="#">Lenovo Yoga 7</a>
                <a href="#">Dell Inspiron 14</a>
                <a href="#">Laptop cấu hình mạnh</a>
                <a href="#">Laptop giá rẻ</a>
                <a href="#">Laptop Gaming</a>
                <a href="#">Laptop học tập, văn phòng</a>
            </div>
        </div>
    </div>

    <div class="footer-main-area">
        <div class="container">
            <div class="footer-links-grid">
                <div class="footer-col">
                    <h4>VĂN PHÒNG GIAO DỊCH</h4>
                    <ul>
                        <li><a href="#">Trang chủ</a></li>
                        <li><a href="#">Laptop mới</a></li>
                        <li><a href="#">Sản phẩm</a></li>
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Tin tức mới</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>THÔNG TIN CÔNG TY</h4>
                    <ul>
                        <li><a href="#">Giới thiệu công ty</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                        <li><a href="#">Gửi góp ý khiếu nại</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>CHÍNH SÁCH CÔNG TY</h4>
                    <ul>
                        <li><a href="#">Chính sách bảo hành - bảo trì</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Chính sách bảo mật thông tin</a></li>
                        <li><a href="#">Hướng dẫn mua hàng - thanh toán</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>

                <div class="footer-col hotline-col">
                    <h4>Tổng đài hỗ trợ</h4>
                    <p>Gọi mua: <a href="tel:0999999999" class="phone-num">0999.999.999</a> (7:30-22:00)</p>
                    <p>Khiếu nại: <a href="tel:0999999999" class="phone-num">0999.999.999</a> (7:30-21:30)</p>
                    <p>Bảo hành: <a href="tel:0666666666" class="phone-num">0666.666.666</a> (7:30-21:00)</p>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="footer-bottom-info">
                <div class="company-details">
                    <h4>CÔNG TY TNHH CHUYÊN LAPTOPTF</h4>
                    <p>Chứng nhận ĐKKD số: 09988776655 do sở KH & ĐT TP.Hà Nội cấp</p>
                    <p>Địa chỉ: Số 21, ngõ 121, Trâu Quỳ, Gia Lâm, Hà Nội</p>
                    <p>Hotline: 0999 999 999</p>
                    <p>Email: laptoptf999@gmail.com</p>
                </div>

                <div class="newsletter-social">
                    <h4>NHẬN THÔNG TIN KHUYẾN MÃI</h4>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Nhập email của bạn vào đây">
                        <button type="button">Đăng ký</button>
                    </form>
                    
                    <div class="social-icons">
                        <a href="#" class="icon-youtube"><i class="ri-youtube-fill"></i></a>
                        <a href="#" class="icon-facebook"><i class="ri-facebook-fill"></i></a>
                        <a href="#" class="icon-tiktok"><i class="ri-tiktok-fill"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-copyright">
        <div class="container">
            <p>&copy; 2025 | Bản quyền thuộc về <a href="#">Cty TNHH LAPTOPTF</a> Cung cấp bởi <a href="#">Laptop TF</a></p>
        </div>
    </div>
</footer>

<!---back-to-top------->
<!------Lên đầu trang------>
<div id="back-to-top" onclick="scrollToTop()">
    <i class="ri-arrow-up-double-line"></i>
</div>
    <script src="{{ asset('asset/js/script.js') }}"></script>
    <!----- LOGIC NÚT BACK TO TOP ----->
    <script>
        
//Lấy nút
const backToTopBtn = document.getElementById("back-to-top");

//Lắng nghe sự kiện cuộn chuột
window.addEventListener("scroll", () => {
    // Nếu cuộn quá 300px thì hiện nút
    if (window.scrollY > 300) {
        backToTopBtn.classList.add("show");
    } else {
        backToTopBtn.classList.remove("show");
    }
});

//Hàm chạy lên đầu trang
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth" // Cuộn mượt mà
    });
}</script>
