<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .email-wrapper { padding: 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background: #d70018; padding: 25px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; text-transform: uppercase; }
        .content { padding: 30px; }
        .content h2 { color: #d70018; font-size: 20px; border-bottom: 2px solid #f4f4f4; padding-bottom: 10px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f9f9f9; }
        .info-label { font-weight: 600; color: #666; width: 35%; }
        .info-value { width: 65%; text-align: right; color: #1d1d1f; }
        .total-box { background: #fff8f8; border: 1px solid #ffebeb; padding: 20px; margin-top: 25px; border-radius: 6px; text-align: center; }
        .total-label { font-size: 14px; color: #d70018; margin-bottom: 5px; }
        .total-price { font-size: 24px; color: #d70018; font-weight: bold; }
        .note { font-size: 13px; color: #888; margin-top: 25px; font-style: italic; border-left: 3px solid #d70018; padding-left: 15px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #aaa; background: #fafafa; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <h1>LaptopTF</h1>
            </div>

            <div class="content">
                <h2>Xác nhận yêu cầu nâng cấp</h2>
                <p>Chào bạn, chúng tôi đã ghi nhận lựa chọn tùy chỉnh của bạn.</p>
                
                <div class="info-row">
                    <div class="info-label">Sản phẩm:</div>
                    <div class="info-value">{{ $data['product_name'] }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Màu sắc:</div>
                    <div class="info-value">{{ $data['color'] }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Nâng cấp:</div>
                    <div class="info-value">
                        @if(count($data['upgrades']) > 0)
                            {{ implode(', ', $data['upgrades']) }}
                        @else
                            Cấu hình mặc định
                        @endif
                    </div>
                </div>

                <div class="total-box">
                    <div class="total-label">Tổng chi phí dự kiến</div>
                    <div class="total-price">{{ $data['total_price'] }}</div>
                </div>

                <p class="note">Nhân viên hỗ trợ sẽ liên hệ với bạn trong vòng 24h tới để hoàn tất thủ tục.</p>
            </div>

            <div class="footer">
                <p>LaptopTF - Uy tín tạo niềm tin</p>
                <p>Số 22 - Trâu Quỳ - Gia Lâm - Hà Nội</p>
                <p>Đây là email tự động, vui lòng không trả lời thư này.</p>
            </div>
        </div>
    </div>
</body>
</html>