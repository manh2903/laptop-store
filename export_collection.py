import json

collection = {
    "info": {
        "_postman_id": "laptop-store-api-testing-2026",
        "name": "Laptop Store - API Testing Collection",
        "description": "Bộ sưu tập kiểm thử API đầy đủ cho hệ thống Website bán máy tính Laptop Store (Laravel 12)",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "variable": [
        {
            "key": "base_url",
            "value": "http://127.0.0.1:8000",
            "type": "string"
        }
    ],
    "item": [
        {
            "name": "1. Module Xác thực & Tài khoản (Auth)",
            "item": [
                {
                    "name": "TC_01 - Kiểm tra SĐT chưa đăng ký (Hợp lệ)",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"phone": "0912345678"}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/check-phone",
                            "host": ["{{base_url}}"],
                            "path": ["check-phone"]
                        },
                        "description": "Kiểm tra số điện thoại mới, hệ thống trả về exists = false (SĐT chưa tồn tại, có thể đăng ký)."
                    },
                    "response": []
                },
                {
                    "name": "TC_02 - Kiểm tra SĐT đã tồn tại",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"phone": "0987654321"}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/check-phone",
                            "host": ["{{base_url}}"],
                            "path": ["check-phone"]
                        },
                        "description": "Kiểm tra số điện thoại đã có trong cơ sở dữ liệu."
                    },
                    "response": []
                },
                {
                    "name": "TC_04 - Xác thực mã OTP (Sai mã)",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"otp": "9999"}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/verify-otp",
                            "host": ["{{base_url}}"],
                            "path": ["verify-otp"]
                        },
                        "description": "Xác thực kích hoạt tài khoản khi người dùng nhập sai mã OTP hoặc phiên đã hết hạn."
                    },
                    "response": []
                }
            ]
        },
        {
            "name": "2. Module Giỏ hàng (Cart)",
            "item": [
                {
                    "name": "TC_08 - Thêm vào giỏ khi CHƯA đăng nhập (Bảo mật 401)",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"product_id": 1}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/gio-hang/them",
                            "host": ["{{base_url}}"],
                            "path": ["gio-hang", "them"]
                        },
                        "description": "Kiểm tra phân quyền: Chưa đăng nhập không được phép thêm vào giỏ hàng, server trả về mã lỗi 401 Unauthorized."
                    },
                    "response": []
                },
                {
                    "name": "TC_09 - Thêm sản phẩm không tồn tại (Lỗi 404)",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"product_id": 99999}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/gio-hang/them",
                            "host": ["{{base_url}}"],
                            "path": ["gio-hang", "them"]
                        },
                        "description": "Kiểm tra sản phẩm không tồn tại trong hệ thống."
                    },
                    "response": []
                },
                {
                    "name": "TC_10 - Cập nhật số lượng giỏ hàng",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"item_id": 1, "quantity": 2}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/gio-hang/cap-nhat",
                            "host": ["{{base_url}}"],
                            "path": ["gio-hang", "cap-nhat"]
                        },
                        "description": "Cập nhật số lượng và tính lại tổng tiền giỏ hàng."
                    },
                    "response": []
                },
                {
                    "name": "TC_13 - Xóa sản phẩm không tồn tại trong giỏ (404)",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({"item_id": 999999}, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/gio-hang/xoa",
                            "host": ["{{base_url}}"],
                            "path": ["gio-hang", "xoa"]
                        },
                        "description": "Xóa một item không tồn tại, trả về 404 error."
                    },
                    "response": []
                }
            ]
        },
        {
            "name": "3. Module Đánh giá (Reviews)",
            "item": [
                {
                    "name": "TC_14 - Gửi đánh giá sản phẩm (Báo lỗi validate khi thiếu sao)",
                    "request": {
                        "method": "POST",
                        "header": [
                            {"key": "Accept", "value": "application/json", "type": "text"},
                            {"key": "Content-Type", "value": "application/json", "type": "text"}
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": json.dumps({
                                "product_id": 1,
                                "rating": None,
                                "content": "Máy rất đẹp và mượt mà!"
                            }, indent=4)
                        },
                        "url": {
                            "raw": "{{base_url}}/reviews",
                            "host": ["{{base_url}}"],
                            "path": ["reviews"]
                        },
                        "description": "Gửi đánh giá thiếu rating, hệ thống sẽ trả về lỗi 422 Unprocessable Entity kèm thông báo validation."
                    },
                    "response": []
                }
            ]
        }
    ]
}

with open("Laptop_Store_API.postman_collection.json", "w", encoding="utf-8") as f:
    json.dump(collection, f, ensure_ascii=False, indent=4)

print("Exported Laptop_Store_API.postman_collection.json successfully!")
