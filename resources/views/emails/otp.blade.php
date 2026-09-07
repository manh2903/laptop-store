<!DOCTYPE html>
<html>
<body style="background-color: #f3f4f6; padding: 20px; font-family: sans-serif;">
    <div style="max-width: 500px; margin: 0 auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="background: #d70018; padding: 20px; text-align: center;">
            <h1 style="color: #fff; margin: 0; font-size: 24px;">TFmember Verification</h1>
        </div>
        <div style="padding: 30px; text-align: center;">
            <p style="color: #555; font-size: 16px;">Xin chào,</p>
            <p style="color: #555;">Đây là mã xác thực đăng ký tài khoản của bạn:</p>
            
            <div style="background: #fdf2f2; color: #d70018; font-size: 36px; font-weight: bold; letter-spacing: 5px; padding: 15px; margin: 20px 0; border: 2px dashed #d70018; border-radius: 8px;">
                {{ $otp }}
            </div>

            <p style="color: #888; font-size: 14px;">Mã có hiệu lực trong <b>1 phút</b>. Tuyệt đối không chia sẻ mã này cho ai.</p>
        </div>
        <div style="background: #eee; padding: 15px; text-align: center; font-size: 12px; color: #777;">
            &copy; 2025 Laptop TF. All rights reserved.
        </div>
    </div>
</body>
</html>