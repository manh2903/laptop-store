<!---------------- Modal Đăng nhập Smember ---------------->
<div id="login-modal" class="login-overlay">
    <div class="login-box">
        <span class="close-login">&times;</span>
        
        <h3 class="login-title">Chào mừng bạn trở lại !</h3>
        
        <div class="login-mascot">
            <img src="/asset/img_linh_vat_tf/rong_tf_chao_don.png" alt="nember chào đón">
        </div>
        
        <p class="login-desc">
            Vui lòng đăng nhập tài khoản TFmember để xem ưu đãi và thanh toán dễ dàng hơn.
        </p>
        
        <div class="login-actions">
    <a href="{{ route('dangky') }}" class="btn-login-outline">
        Đăng ký
    </a>

    <a id="btn-login-link" href="{{ route('login') }}" class="btn-login-solid">
        Đăng nhập
    </a>
</div>
    </div>
</div>