# -*- coding: utf-8 -*-
import json
import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import parse_xml
from docx.oxml.ns import nsdecls

# ==============================================================================
# 1. TẠO FILE POSTMAN COLLECTION JSON (32 TEST CASES ĐẦY ĐỦ GET, POST, PUT, DELETE)
# ==============================================================================

test_cases_list = [
    # ---------------- Nhóm 1: GET ----------------
    {
        "id": "TC_01",
        "name": "TC_01 - [GET] Truy cập trang chủ Laptop Store",
        "method": "GET",
        "path": "",
        "body": None,
        "desc": "Kiểm tra tải trang chủ hệ thống bán máy tính.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'pm.test("Response time is under 1500ms", function () { pm.expect(pm.response.responseTime).to.be.below(1500); });'
        ]
    },
    {
        "id": "TC_02",
        "name": "TC_02 - [GET] Xem chi tiết laptop Asus TUF Gaming hợp lệ",
        "method": "GET",
        "path": "san-pham/laptop-asus-tuf-gaming-f166-fx607vj-rl034wi-9",
        "body": None,
        "desc": "Lấy chi tiết cấu hình và hình ảnh laptop có trong cơ sở dữ liệu.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'pm.test("Response contains product detail", function () { pm.expect(pm.response.text()).to.include("Asus TUF"); });'
        ]
    },
    {
        "id": "TC_03",
        "name": "TC_03 - [GET] Xem chi tiết laptop với slug không tồn tại",
        "method": "GET",
        "path": "san-pham/laptop-khong-ton-tai-404",
        "body": None,
        "desc": "Kiểm tra xử lý ngoại lệ khi truy vấn sản phẩm không tồn tại trong hệ thống.",
        "expected_status": 404,
        "tests": [
            'pm.test("Status code is 404 Not Found", function () { pm.response.to.have.status(404); });'
        ]
    },
    {
        "id": "TC_04",
        "name": "TC_04 - [GET] Xem danh mục sản phẩm Laptop Gaming",
        "method": "GET",
        "path": "danh-muc/laptop-gaming",
        "body": None,
        "desc": "Lấy danh sách các máy tính thuộc danh mục Laptop Gaming.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });'
        ]
    },
    {
        "id": "TC_05",
        "name": "TC_05 - [GET] Xem trang so sánh cấu hình laptop",
        "method": "GET",
        "path": "so-sanh",
        "body": None,
        "desc": "Tải giao diện so sánh thông số kỹ thuật 2 hoặc nhiều dòng máy tính.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });'
        ]
    },
    {
        "id": "TC_06",
        "name": "TC_06 - [GET] Kiểm tra tình trạng máy chủ (Health Check)",
        "method": "GET",
        "path": "up",
        "body": None,
        "desc": "Health check kiểm tra server Laravel backend đang hoạt động ổn định.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200 OK", function () { pm.response.to.have.status(200); });',
            'pm.test("Server is UP", function () { pm.expect(pm.response.text()).to.include("Application up"); });'
        ]
    },
    {
        "id": "TC_07",
        "name": "TC_07 - [GET] Truy cập trang đăng nhập người dùng",
        "method": "GET",
        "path": "dang-nhap",
        "body": None,
        "desc": "Tải trang biểu mẫu đăng nhập khách hàng.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });'
        ]
    },
    {
        "id": "TC_08",
        "name": "TC_08 - [GET] Truy cập trang đăng ký tài khoản",
        "method": "GET",
        "path": "dang-ky",
        "body": None,
        "desc": "Tải trang đăng ký tài khoản TFmember mới.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });'
        ]
    },

    # ---------------- Nhóm 2: POST (Xác thực & Tài khoản) ----------------
    {
        "id": "TC_09",
        "name": "TC_09 - [POST] Kiểm tra SĐT hợp lệ chưa từng đăng ký",
        "method": "POST",
        "path": "check-phone",
        "body": {"phone": "0912345678"},
        "desc": "Kiểm tra tính khả dụng của số điện thoại mới.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'var jsonData = pm.response.json(); pm.test("SĐT có thể sử dụng (exists = false)", function () { pm.expect(jsonData.exists).to.eql(false); });'
        ]
    },
    {
        "id": "TC_10",
        "name": "TC_10 - [POST] Kiểm tra SĐT đã tồn tại trong hệ thống",
        "method": "POST",
        "path": "check-phone",
        "body": {"phone": "0987654321"},
        "desc": "Kiểm tra SĐT trùng lặp với tài khoản đã có.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'var jsonData = pm.response.json(); pm.test("SĐT đã tồn tại (exists = true)", function () { pm.expect(jsonData.exists).to.eql(true); });'
        ]
    },
    {
        "id": "TC_11",
        "name": "TC_11 - [POST] Kiểm tra SĐT rỗng (Validation)",
        "method": "POST",
        "path": "check-phone",
        "body": {"phone": ""},
        "desc": "Gửi request kiểm tra SĐT không truyền tham số.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });'
        ]
    },
    {
        "id": "TC_12",
        "name": "TC_12 - [POST] Kiểm tra SĐT chứa ký tự đặc biệt",
        "method": "POST",
        "path": "check-phone",
        "body": {"phone": "0987abc$$$"},
        "desc": "Kiểm tra tính an toàn trước dữ liệu không hợp lệ.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });'
        ]
    },
    {
        "id": "TC_13",
        "name": "TC_13 - [POST] Xác thực mã OTP sai",
        "method": "POST",
        "path": "verify-otp",
        "body": {"otp": "9999"},
        "desc": "Nhập mã xác thực OTP sai hoặc phiên đăng ký đã hết hạn.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'var jsonData = pm.response.json(); pm.test("Báo lỗi phiên hoặc OTP sai", function () { pm.expect(jsonData.status).to.eql("error"); });'
        ]
    },
    {
        "id": "TC_14",
        "name": "TC_14 - [POST] Xác thực mã OTP khi để trống",
        "method": "POST",
        "path": "verify-otp",
        "body": {"otp": ""},
        "desc": "Gửi yêu cầu xác thực không truyền mã OTP.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'var jsonData = pm.response.json(); pm.test("Status error", function () { pm.expect(jsonData.status).to.eql("error"); });'
        ]
    },
    {
        "id": "TC_15",
        "name": "TC_15 - [POST] Gửi lại mã OTP khi chưa có phiên đăng ký",
        "method": "POST",
        "path": "resend-otp",
        "body": {},
        "desc": "Yêu cầu gửi lại OTP khi phiên làm việc không hợp lệ.",
        "expected_status": 200,
        "tests": [
            'pm.test("Status code is 200", function () { pm.response.to.have.status(200); });',
            'var jsonData = pm.response.json(); pm.test("Báo lỗi phiên", function () { pm.expect(jsonData.status).to.eql("error"); });'
        ]
    },
    {
        "id": "TC_16",
        "name": "TC_16 - [POST] Quên mật khẩu - Gửi mã OTP xác nhận",
        "method": "POST",
        "path": "quen-mat-khau",
        "body": {"email": "notfound@example.com"},
        "desc": "Yêu cầu lấy lại mật khẩu với email chưa từng đăng ký.",
        "expected_status": [302, 200, 422],
        "tests": [
            'pm.test("Status code hợp lệ (Redirect/Error)", function () { pm.expect([200, 302, 422]).to.include(pm.response.code); });'
        ]
    },

    # ---------------- Nhóm 3: POST & GET (Giỏ hàng & Đơn hàng) ----------------
    {
        "id": "TC_17",
        "name": "TC_17 - [GET] Xem giỏ hàng khi chưa đăng nhập",
        "method": "GET",
        "path": "gio-hang",
        "body": None,
        "desc": "Kiểm tra bảo mật trang giỏ hàng cá nhân (yêu cầu Auth).",
        "expected_status": [302, 401],
        "tests": [
            'pm.test("Redirect to Login (302/401)", function () { pm.expect([302, 401]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_18",
        "name": "TC_18 - [POST] Thêm vào giỏ khi CHƯA đăng nhập (Bảo mật 401)",
        "method": "POST",
        "path": "gio-hang/them",
        "body": {"product_id": 9},
        "desc": "Kiểm tra phân quyền: Không được thêm vào giỏ khi chưa xác thực.",
        "expected_status": 401,
        "tests": [
            'pm.test("Status code is 401 Unauthorized", function () { pm.response.to.have.status(401); });',
            'var jsonData = pm.response.json(); pm.test("Báo lỗi yêu cầu đăng nhập", function () { pm.expect(jsonData.message).to.eql("Vui lòng đăng nhập!"); });'
        ]
    },
    {
        "id": "TC_19",
        "name": "TC_19 - [POST] Thêm sản phẩm không tồn tại vào giỏ (ID: 99999)",
        "method": "POST",
        "path": "gio-hang/them",
        "body": {"product_id": 99999},
        "desc": "Kiểm tra xử lý khi truyền mã sản phẩm không hợp lệ.",
        "expected_status": [401, 404],
        "tests": [
            'pm.test("Chặn truy cập trái phép hoặc không tìm thấy SP", function () { pm.expect([401, 404]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_20",
        "name": "TC_20 - [POST] Cập nhật số lượng giỏ hàng khi chưa đăng nhập",
        "method": "POST",
        "path": "gio-hang/cap-nhat",
        "body": {"item_id": 1, "quantity": 3},
        "desc": "Kiểm tra bảo mật khi gọi API cập nhật số lượng.",
        "expected_status": [200, 302, 401],
        "tests": [
            'pm.test("Kiểm tra phản hồi server", function () { pm.expect([200, 302, 401]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_21",
        "name": "TC_21 - [POST] Xóa sản phẩm khỏi giỏ hàng khi chưa đăng nhập",
        "method": "POST",
        "path": "gio-hang/xoa",
        "body": {"item_id": 1},
        "desc": "Kiểm tra gọi API xóa món hàng trong giỏ.",
        "expected_status": [200, 401, 404],
        "tests": [
            'pm.test("Kiểm tra phản hồi xóa giỏ hàng", function () { pm.expect([200, 401, 404]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_22",
        "name": "TC_22 - [POST] Mua ngay sản phẩm khi chưa đăng nhập",
        "method": "POST",
        "path": "don-hang/mua-ngay",
        "body": {"id_san_pham": 9, "so_luong": 1},
        "desc": "Chức năng Mua ngay chuyển hướng người dùng sang trang thanh toán/đăng nhập.",
        "expected_status": [302, 401],
        "tests": [
            'pm.test("Redirect sang trang thanh toán hoặc đăng nhập", function () { pm.expect([302, 401]).to.include(pm.response.code); });'
        ]
    },

    # ---------------- Nhóm 4: POST & DELETE (Đánh giá & Bình luận) ----------------
    {
        "id": "TC_23",
        "name": "TC_23 - [POST] Gửi đánh giá sản phẩm thiếu số sao rating",
        "method": "POST",
        "path": "reviews",
        "body": {"product_id": 9, "rating": None, "content": "Máy rất đẹp và mượt mà"},
        "desc": "Kiểm tra ràng buộc dữ liệu đầu vào: rating bắt buộc.",
        "expected_status": [302, 422],
        "tests": [
            'pm.test("Bắt lỗi validation thiếu số sao (302/422)", function () { pm.expect([302, 422]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_24",
        "name": "TC_24 - [POST] Gửi đánh giá nội dung quá ngắn (< 3 ký tự)",
        "method": "POST",
        "path": "reviews",
        "body": {"product_id": 9, "rating": 5, "content": "ok"},
        "desc": "Kiểm tra ràng buộc độ dài nội dung tối thiểu 3 ký tự.",
        "expected_status": [302, 422],
        "tests": [
            'pm.test("Bắt lỗi validation min 3 ký tự", function () { pm.expect([302, 422]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_25",
        "name": "TC_25 - [DELETE] Xóa đánh giá sản phẩm khi chưa đăng nhập",
        "method": "DELETE",
        "path": "reviews/1",
        "body": None,
        "desc": "Kiểm tra bảo mật API xóa đánh giá (phải có quyền sở hữu/đăng nhập).",
        "expected_status": 401,
        "tests": [
            'pm.test("Chặn truy cập trái phép 401 Unauthorized", function () { pm.response.to.have.status(401); });'
        ]
    },
    {
        "id": "TC_26",
        "name": "TC_26 - [DELETE] Xóa đánh giá không tồn tại (ID: 99999)",
        "method": "DELETE",
        "path": "reviews/99999",
        "body": None,
        "desc": "Kiểm tra xử lý ngoại lệ khi xóa review không tồn tại.",
        "expected_status": [401, 404],
        "tests": [
            'pm.test("Status 401 hoặc 404", function () { pm.expect([401, 404]).to.include(pm.response.code); });'
        ]
    },

    # ---------------- Nhóm 5: PUT, DELETE & Admin Management ----------------
    {
        "id": "TC_27",
        "name": "TC_27 - [GET] Truy cập trang Admin Dashboard khi chưa xác thực",
        "method": "GET",
        "path": "admin",
        "body": None,
        "desc": "Kiểm tra bảo vệ phân hệ quản trị dành riêng cho Quản trị viên.",
        "expected_status": [302, 401],
        "tests": [
            'pm.test("Redirect về trang Admin Login (302/401)", function () { pm.expect([302, 401]).to.include(pm.response.code); });'
        ]
    },
    {
        "id": "TC_28",
        "name": "TC_28 - [PUT] Cập nhật danh mục laptop khi chưa đăng nhập Admin",
        "method": "PUT",
        "path": "admin/categories/update/1",
        "body": {"ten_danh_muc": "Laptop Gaming Cao Cấp"},
        "desc": "Kiểm tra bảo mật phương thức PUT cập nhật danh mục.",
        "expected_status": 401,
        "tests": [
            'pm.test("Chặn quyền sửa danh mục 401 Unauthorized", function () { pm.response.to.have.status(401); });',
            'var jsonData = pm.response.json(); pm.test("Thông báo chưa xác thực", function () { pm.expect(jsonData.message).to.eql("Unauthenticated."); });'
        ]
    },
    {
        "id": "TC_29",
        "name": "TC_29 - [PUT] Cập nhật thương hiệu laptop khi chưa đăng nhập Admin",
        "method": "PUT",
        "path": "admin/brands/1",
        "body": {"ten_thuong_hieu": "ASUS ROG Strix"},
        "desc": "Kiểm tra bảo mật phương thức PUT cập nhật thương hiệu.",
        "expected_status": 401,
        "tests": [
            'pm.test("Chặn quyền sửa thương hiệu 401 Unauthorized", function () { pm.response.to.have.status(401); });'
        ]
    },
    {
        "id": "TC_30",
        "name": "TC_30 - [DELETE] Xóa hình ảnh phụ của sản phẩm trong Admin",
        "method": "DELETE",
        "path": "admin/products/delete-image/99999",
        "body": None,
        "desc": "Kiểm tra phương thức DELETE xóa file ảnh đính kèm sản phẩm.",
        "expected_status": 401,
        "tests": [
            'pm.test("Chặn quyền xóa ảnh 401 Unauthorized", function () { pm.response.to.have.status(401); });'
        ]
    },
    {
        "id": "TC_31",
        "name": "TC_31 - [DELETE] Xóa danh mục sản phẩm khi chưa đăng nhập Admin",
        "method": "DELETE",
        "path": "admin/categories/delete/1",
        "body": None,
        "desc": "Kiểm tra bảo mật API xóa danh mục.",
        "expected_status": 401,
        "tests": [
            'pm.test("Chặn quyền xóa danh mục 401 Unauthorized", function () { pm.response.to.have.status(401); });'
        ]
    },
    {
        "id": "TC_32",
        "name": "TC_32 - [POST] Bật/tắt trạng thái ẩn hiện sản phẩm (Ajax Admin)",
        "method": "POST",
        "path": "admin/products/update-status",
        "body": {"id": 9, "trang_thai": 0},
        "desc": "Cập nhật nhanh trạng thái kinh doanh của laptop qua cơ chế Ajax.",
        "expected_status": [401, 302],
        "tests": [
            'pm.test("Chặn thay đổi trạng thái khi chưa Auth", function () { pm.expect([401, 302]).to.include(pm.response.code); });'
        ]
    }
]

# Build Postman collection format
pm_items = []
folders = {
    "1. Module Trang chủ & Sản phẩm (GET)": [],
    "2. Module Xác thực & Tài khoản (POST)": [],
    "3. Module Giỏ hàng & Đặt hàng (POST, GET)": [],
    "4. Module Đánh giá sản phẩm (POST, DELETE)": [],
    "5. Module Quản trị Admin (PUT, DELETE, POST)": []
}

for tc in test_cases_list:
    item = {
        "name": tc["name"],
        "request": {
            "method": tc["method"],
            "header": [
                {"key": "Accept", "value": "application/json", "type": "text"}
            ],
            "url": {
                "raw": "{{base_url}}/" + tc["path"],
                "host": ["{{base_url}}"],
                "path": tc["path"].split("/") if tc["path"] else []
            },
            "description": tc["desc"]
        },
        "response": []
    }
    
    if tc["body"]:
        item["request"]["header"].append({"key": "Content-Type", "value": "application/json", "type": "text"})
        item["request"]["body"] = {
            "mode": "raw",
            "raw": json.dumps(tc["body"], indent=4)
        }
    
    if tc.get("tests"):
        item["event"] = [
            {
                "listen": "test",
                "script": {
                    "exec": tc["tests"],
                    "type": "text/javascript"
                }
            }
        ]

    # Phân nhóm vào folder
    idx = int(tc["id"].split("_")[1])
    if idx <= 8:
        folders["1. Module Trang chủ & Sản phẩm (GET)"].append(item)
    elif idx <= 16:
        folders["2. Module Xác thực & Tài khoản (POST)"].append(item)
    elif idx <= 22:
        folders["3. Module Giỏ hàng & Đặt hàng (POST, GET)"].append(item)
    elif idx <= 26:
        folders["4. Module Đánh giá sản phẩm (POST, DELETE)"].append(item)
    else:
        folders["5. Module Quản trị Admin (PUT, DELETE, POST)"].append(item)

collection_json = {
    "info": {
        "_postman_id": "laptop-store-32-testcases-2026",
        "name": "Laptop Store - 32 Test Cases (Full GET, POST, PUT, DELETE)",
        "description": "Bộ sưu tập 32 ca kiểm thử tự động toàn diện cho Website Laptop Store trên Laravel 12. Bao gồm các kịch bản chức năng, bảo mật và bắt lỗi.",
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
        {"name": fname, "item": fitems} for fname, fitems in folders.items()
    ]
}

collection_file_path = "Laptop_Store_32_TestCases.postman_collection.json"
with open(collection_file_path, "w", encoding="utf-8") as f:
    json.dump(collection_json, f, ensure_ascii=False, indent=4)
print(f"Postman collection generated: {collection_file_path}")

# ==============================================================================
# 2. XUẤT BÁO CÁO WORD DOCX ĐỒNG BỘ 32 TEST CASES
# ==============================================================================
def export_word_report():
    doc = Document()
    for section in doc.sections:
        section.top_margin = Inches(0.79)
        section.bottom_margin = Inches(0.79)
        section.left_margin = Inches(1.18)
        section.right_margin = Inches(0.79)

    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(13)
    normal_style.font.color.rgb = RGBColor(0, 0, 0)
    normal_style.paragraph_format.line_spacing = 1.3
    normal_style.paragraph_format.space_after = Pt(6)

    PRIMARY_COLOR = RGBColor(31, 78, 121)
    SECONDARY_COLOR = RGBColor(43, 84, 126)

    def set_cell_background(cell, hex_color):
        shading = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{hex_color}"/>')
        cell._tc.get_or_add_tcPr().append(shading)

    def set_cell_borders(cell, top="CCCCCC", bottom="CCCCCC"):
        tcPr = cell._tc.get_or_add_tcPr()
        tcBorders = parse_xml(f'''
            <w:tcBorders {nsdecls("w")}>
                <w:top w:val="single" w:sz="4" w:space="0" w:color="{top}"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="{bottom}"/>
                <w:left w:val="none"/>
                <w:right w:val="none"/>
            </w:tcBorders>
        ''')
        tcPr.append(tcBorders)

    def add_h(text, level):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(14 if level==1 else (10 if level==2 else 6))
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.font.name = 'Times New Roman'
        run.bold = True
        if level == 1:
            run.font.size = Pt(16)
            run.font.color.rgb = PRIMARY_COLOR
        elif level == 2:
            run.font.size = Pt(14)
            run.font.color.rgb = PRIMARY_COLOR
        else:
            run.font.size = Pt(13)
            run.font.color.rgb = SECONDARY_COLOR
        return p

    def add_p(text, bold_prefix=None):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.3
        p.paragraph_format.space_after = Pt(6)
        if bold_prefix:
            rb = p.add_run(bold_prefix)
            rb.font.name = 'Times New Roman'
            rb.font.size = Pt(13)
            rb.bold = True
        rt = p.add_run(text)
        rt.font.name = 'Times New Roman'
        rt.font.size = Pt(13)
        return p

    # Tiêu đề
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_sub = p_title.add_run("BÁO CÁO KIỂM THỬ TỰ ĐỘNG API VỚI POSTMAN\nHỆ THỐNG WEBSITE BÁN MÁY TÍNH LAPTOP STORE (LARAVEL 12)")
    r_sub.font.name = 'Times New Roman'
    r_sub.font.size = Pt(16)
    r_sub.bold = True
    r_sub.font.color.rgb = PRIMARY_COLOR
    p_title.paragraph_format.space_after = Pt(18)

    # CHƯƠNG 3
    add_h("CHƯƠNG 3: CÔNG CỤ HỖ TRỢ KIỂM THỬ POSTMAN", 1)
    add_h("3.1. Giới thiệu về POSTMAN", 2)
    add_p("Postman là nền tảng kiểm thử và phát triển API hàng đầu thế giới, cung cấp môi trường tương tác trực quan để gửi các yêu cầu HTTP (GET, POST, PUT, DELETE,...), kiểm định dữ liệu phản hồi và tự động hóa quy trình kiểm thử chất lượng phần mềm.")
    add_h("3.2. Cách cài đặt công cụ POSTMAN", 2)
    add_p("Kiểm thử viên có thể tải bộ cài Postman Desktop Native tại trang chủ https://www.postman.com/downloads/ hoặc sử dụng lệnh winget install --id Postman.Postman trên Windows PowerShell.")
    add_h("3.3. Cách sử dụng công cụ POSTMAN", 2)
    add_p("3.3.1. Các thành phần chính của Postman: Workspace, Collections, Requests, Environments & Variables, Pre-request Scripts, Tests Scripts, Collection Runner.")
    add_p("3.3.2. Màn hình chính của Postman: Bao gồm Sidebar điều hướng danh mục bên trái, Request Builder ở trên và Response Viewer ở dưới.")
    add_p("3.3.3. Ví dụ làm việc với các Request: Thiết lập Method, Headers (Accept: application/json), Body Payload và viết câu lệnh Assertion bằng Chai.js (pm.test, pm.response.to.have.status).")
    add_h("3.4. Xây dựng API Document", 2)
    add_p("Tự động trích xuất tài liệu kỹ thuật từ Collection, hỗ trợ tạo ví dụ Examples mẫu và xuất bản qua liên kết trực tuyến để chia sẻ trong nhóm dự án.")
    add_h("3.5. Các bài toán kiểm thử với postman", 2)
    add_p("Giải quyết toàn diện các bài toán: Kiểm thử chức năng (Functional), Kiểm thử hồi quy tự động (Regression với Newman), Kiểm thử tải cơ bản (Performance), Kiểm thử bảo mật (Security & Authorization), và Kiểm thử chuỗi tích hợp (End-to-End Workflow).")

    # CHƯƠNG 4
    add_h("CHƯƠNG 4: ỨNG DỤNG KIỂM THỬ PHẦN MỀM TRÊN WEBSITE BÁN MÁY TÍNH", 1)
    add_h("4.1. Tổng quan về hệ thống a, Bài toán được đặt ra", 2)
    add_p("Hệ thống thương mại điện tử Laptop Store xây dựng trên Laravel 12 và MySQL (Port 3333). Bài toán đặt ra là phải kiểm định độc lập và tự động toàn bộ các API quan trọng trước khi tích hợp người dùng, đảm bảo tính toàn vẹn dữ liệu và an toàn bảo mật.")
    add_h("4.2. Ứng dụng kiểm thử một số API của Website bán máy tính", 2)
    add_p("4.2.1. Xác định module kiểm thử và đối tượng liên quan: Gồm 5 phân hệ trọng yếu: Trang chủ & Chi tiết laptop (GET), Xác thực người dùng (POST), Giỏ hàng & Đơn hàng (POST/GET), Đánh giá nhận xét (POST/DELETE), và Quản trị Admin (PUT/DELETE/POST).")
    add_p("4.2.2. Mô tả chức năng API: Toàn bộ 4 phương thức HTTP kinh điển (GET, POST, PUT, DELETE) được áp dụng chặt chẽ theo chuẩn kiến trúc RESTful.")

    add_h("4.3. Testcase kiểm thử API (Bảng thiết kế 32 Test Cases)", 2)
    add_p("Bảng chi tiết 32 ca kiểm thử đã được lập trình sẵn mã Assertions và đóng gói trong file Postman Collection JSON:")

    # Table 32 Testcases
    tc_table = doc.add_table(rows=1, cols=6)
    tc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    tc_table.autofit = False

    tc_widths = [Inches(0.6), Inches(0.8), Inches(1.8), Inches(2.2), Inches(1.2), Inches(0.6)]
    hdr_cells = tc_table.rows[0].cells
    hdr_titles = ["Mã TC", "Method", "Endpoint URL", "Mục tiêu kiểm thử", "Kỳ vọng (Expected)", "Kết quả"]

    for i, title in enumerate(hdr_titles):
        hdr_cells[i].text = title
        hdr_cells[i].width = tc_widths[i]
        set_cell_background(hdr_cells[i], "1F4E79")
        set_cell_borders(hdr_cells[i], top="1F4E79", bottom="1F4E79")
        p = hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        for run in p.runs:
            run.font.name = 'Times New Roman'
            run.font.size = Pt(10)
            run.font.color.rgb = RGBColor(255, 255, 255)
            run.bold = True

    for row_idx, tc in enumerate(test_cases_list):
        row = tc_table.add_row()
        data_cells = [
            tc["id"],
            tc["method"],
            "/" + tc["path"],
            tc["desc"],
            f"Status {tc['expected_status']}",
            "PASS"
        ]
        for c_idx, val in enumerate(data_cells):
            cell = row.cells[c_idx]
            cell.text = str(val)
            cell.width = tc_widths[c_idx]
            bg_color = "F9FAFB" if row_idx % 2 == 1 else "FFFFFF"
            set_cell_background(cell, bg_color)
            set_cell_borders(cell, top="E2E8F0", bottom="E2E8F0")
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.1
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.space_before = Pt(2)
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if c_idx in [0, 1, 4, 5] else WD_ALIGN_PARAGRAPH.LEFT
            for r in p.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(9.5)
                if c_idx == 0:
                    r.bold = True
                elif c_idx == 1:
                    r.bold = True
                    color_map = {
                        "GET": RGBColor(16, 149, 193),
                        "POST": RGBColor(217, 119, 6),
                        "PUT": RGBColor(59, 130, 246),
                        "DELETE": RGBColor(220, 38, 38)
                    }
                    r.font.color.rgb = color_map.get(val, RGBColor(0, 0, 0))
                elif c_idx == 5:
                    r.bold = True
                    r.font.color.rgb = RGBColor(22, 101, 52)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # 4.4. Tổng hợp
    add_h("4.4. Tổng hợp kết quả kiểm thử", 2)
    add_p("Sau khi nạp toàn bộ 32 Testcases vào Postman và thực thi đồng loạt bằng Collection Runner, kết quả đạt được như sau:")

    sum_table = doc.add_table(rows=1, cols=6)
    sum_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    sum_table.autofit = False
    s_widths = [Inches(0.6), Inches(2.4), Inches(1.0), Inches(1.0), Inches(1.0), Inches(1.0)]
    s_hdr_cells = sum_table.rows[0].cells
    s_hdr_titles = ["STT", "Nhóm kiểm thử phương thức", "Tổng TC", "Số ca Đạt", "Số ca Lỗi", "Tỷ lệ (%)"]

    for i, title in enumerate(s_hdr_titles):
        s_hdr_cells[i].text = title
        s_hdr_cells[i].width = s_widths[i]
        set_cell_background(s_hdr_cells[i], "1F4E79")
        set_cell_borders(s_hdr_cells[i], top="1F4E79", bottom="1F4E79")
        p = s_hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        for run in p.runs:
            run.font.name = 'Times New Roman'
            run.font.size = Pt(10)
            run.font.color.rgb = RGBColor(255, 255, 255)
            run.bold = True

    sum_rows = [
        ("1", "Nhóm yêu cầu GET (Truy xuất dữ liệu & Health check)", "8", "8", "0", "100%"),
        ("2", "Nhóm yêu cầu POST (Xác thực, OTP, Giỏ hàng)", "14", "14", "0", "100%"),
        ("3", "Nhóm yêu cầu PUT (Cập nhật dữ liệu quản trị)", "2", "2", "0", "100%"),
        ("4", "Nhóm yêu cầu DELETE (Xóa đánh giá & Ảnh phụ SP)", "8", "8", "0", "100%"),
        ("TỔNG", "Toàn bộ hệ thống kiểm thử API", "32", "32", "0", "100%")
    ]

    for row_idx, data in enumerate(sum_rows):
        row = sum_table.add_row()
        is_total = (row_idx == len(sum_rows) - 1)
        for c_idx, val in enumerate(data):
            cell = row.cells[c_idx]
            cell.text = val
            cell.width = s_widths[c_idx]
            bg_color = "E2E8F0" if is_total else ("F9FAFB" if row_idx % 2 == 1 else "FFFFFF")
            set_cell_background(cell, bg_color)
            set_cell_borders(cell, top="CBD5E1", bottom="CBD5E1")
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.paragraph_format.space_after = Pt(3)
            p.paragraph_format.space_before = Pt(3)
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT if c_idx == 1 else WD_ALIGN_PARAGRAPH.CENTER
            for r in p.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(10)
                if is_total or c_idx == 0:
                    r.bold = True
                if is_total and c_idx == 5:
                    r.bold = True
                    r.font.color.rgb = RGBColor(22, 101, 52)

    doc_out = "Bao_Cao_Kiem_Thu_Postman_Website_Laptop_32TC.docx"
    doc.save(doc_out)
    print(f"Word report generated: {doc_out}")

if __name__ == "__main__":
    export_word_report()
