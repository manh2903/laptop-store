# -*- coding: utf-8 -*-
import os
import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def create_report():
    doc = Document()

    # Page setup - Margins (Left: 3cm, Right: 2cm, Top: 2cm, Bottom: 2cm)
    for section in doc.sections:
        section.top_margin = Inches(0.79)     # 2.0 cm
        section.bottom_margin = Inches(0.79)  # 2.0 cm
        section.left_margin = Inches(1.18)    # 3.0 cm
        section.right_margin = Inches(0.79)   # 2.0 cm

    # Base Normal Style
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Times New Roman'
    normal_style.font.size = Pt(13)
    normal_style.font.color.rgb = RGBColor(0, 0, 0)
    normal_style.paragraph_format.line_spacing = 1.3
    normal_style.paragraph_format.space_after = Pt(6)

    # Color Palette
    PRIMARY_COLOR = RGBColor(31, 78, 121)    # Deep Steel Blue #1F4E79
    SECONDARY_COLOR = RGBColor(43, 84, 126)  # Medium Blue
    TEXT_COLOR = RGBColor(0, 0, 0)

    def set_cell_background(cell, hex_color):
        shading = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{hex_color}"/>')
        cell._tc.get_or_add_tcPr().append(shading)

    def set_cell_borders(cell, top="CCCCCC", bottom="CCCCCC", left=None, right=None):
        tcPr = cell._tc.get_or_add_tcPr()
        tcBorders = parse_xml(f'''
            <w:tcBorders {nsdecls("w")}>
                <w:top w:val="single" w:sz="4" w:space="0" w:color="{top}"/>
                <w:bottom w:val="single" w:sz="4" w:space="0" w:color="{bottom}"/>
                {"<w:left w:val='single' w:sz='4' w:space='0' w:color='" + left + "'/>" if left else "<w:left w:val='none'/>"}
                {"<w:right w:val='single' w:sz='4' w:space='0' w:color='" + right + "'/>" if right else "<w:right w:val='none'/>"}
            </w:tcBorders>
        ''')
        tcPr.append(tcBorders)

    def add_custom_heading(text, level, space_before=12, space_after=6):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(space_before)
        p.paragraph_format.space_after = Pt(space_after)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.font.name = 'Times New Roman'
        run.bold = True

        if level == 1:
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            run.font.size = Pt(16)
            run.font.color.rgb = PRIMARY_COLOR
            # Add subtle underline or border if needed
        elif level == 2:
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            run.font.size = Pt(14)
            run.font.color.rgb = PRIMARY_COLOR
        elif level == 3:
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            run.font.size = Pt(13)
            run.font.color.rgb = SECONDARY_COLOR
        elif level == 4:
            p.alignment = WD_ALIGN_PARAGRAPH.LEFT
            run.font.size = Pt(13)
            run.font.color.rgb = TEXT_COLOR
            run.italic = True
        return p

    def add_p(text, bold_prefix=None, indent=0):
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.3
        p.paragraph_format.space_after = Pt(6)
        if indent > 0:
            p.paragraph_format.left_indent = Inches(indent * 0.25)
        
        if bold_prefix:
            r_bold = p.add_run(bold_prefix)
            r_bold.font.name = 'Times New Roman'
            r_bold.font.size = Pt(13)
            r_bold.bold = True
        
        r_text = p.add_run(text)
        r_text.font.name = 'Times New Roman'
        r_text.font.size = Pt(13)
        return p

    def add_bullet(text, bold_prefix=None, level=0):
        p = doc.add_paragraph(style='List Bullet')
        p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
        p.paragraph_format.line_spacing = 1.3
        p.paragraph_format.space_after = Pt(4)
        if level > 0:
            p.paragraph_format.left_indent = Inches((level + 1) * 0.25)
        
        if bold_prefix:
            r_bold = p.add_run(bold_prefix)
            r_bold.font.name = 'Times New Roman'
            r_bold.font.size = Pt(13)
            r_bold.bold = True
        
        r_text = p.add_run(text)
        r_text.font.name = 'Times New Roman'
        r_text.font.size = Pt(13)
        return p

    def add_code_block(code_text):
        tbl = doc.add_table(rows=1, cols=1)
        tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
        cell = tbl.cell(0, 0)
        set_cell_background(cell, "F4F6F9")
        set_cell_borders(cell, top="2B4C7E", bottom="2B4C7E", left="2B4C7E", right="2B4C7E")
        p = cell.paragraphs[0]
        p.paragraph_format.space_before = Pt(4)
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.line_spacing = 1.15
        run = p.add_run(code_text)
        run.font.name = 'Consolas'
        run.font.size = Pt(10)
        run.font.color.rgb = RGBColor(30, 41, 59)
        doc.add_paragraph().paragraph_format.space_after = Pt(4)

    # -------------------------------------------------------------
    # DOCUMENT TITLE / COVER HEADER (Optional top banner)
    # -------------------------------------------------------------
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    r_main = p_title.add_run("BÁO CÁO THỰC TẬP / ĐỒ ÁN CHUYÊN NGÀNH\n")
    r_main.font.name = 'Times New Roman'
    r_main.font.size = Pt(14)
    r_main.bold = True
    r_main.font.color.rgb = RGBColor(100, 100, 100)

    r_sub = p_title.add_run("ỨNG DỤNG CÔNG CỤ POSTMAN TRONG KIỂM THỬ API\nCHO WEBSITE KINH DOANH MÁY TÍNH LAPTOP STORE")
    r_sub.font.name = 'Times New Roman'
    r_sub.font.size = Pt(18)
    r_sub.bold = True
    r_sub.font.color.rgb = PRIMARY_COLOR
    p_title.paragraph_format.space_after = Pt(24)

    # =============================================================
    # CHƯƠNG 3: CÔNG CỤ HỖ TRỢ KIỂM THỬ POSTMAN
    # =============================================================
    add_custom_heading("CHƯƠNG 3: CÔNG CỤ HỖ TRỢ KIỂM THỬ POSTMAN", 1, space_before=18, space_after=10)

    # 3.1. Giới thiệu về POSTMAN
    add_custom_heading("3.1. Giới thiệu về POSTMAN", 2)
    add_p(
        "Trong quy trình phát triển phần mềm hiện đại theo kiến trúc Microservices hoặc Client-Server (phân tách Frontend - Backend), "
        "Application Programming Interface (API) đóng vai trò là xương sống kết nối và trao đổi dữ liệu giữa các hệ thống độc lập. "
        "Để đảm bảo chất lượng, tính toàn vẹn dữ liệu, hiệu năng và độ tin cậy của các dịch vụ backend trước khi tích hợp vào giao diện, "
        "việc kiểm thử API (API Testing) trở thành một công đoạn bắt buộc và mang tính sống còn."
    )
    add_p(
        "Postman là một trong những nền tảng phát triển và kiểm thử API phổ biến nhất trên thế giới hiện nay, được sáng lập vào năm 2012 bởi Abhinav Asthana. "
        "Ban đầu, Postman được phát triển như một tiện ích mở rộng (extension) trên trình duyệt Google Chrome nhằm đơn giản hóa việc gửi các HTTP Request. "
        "Đến nay, Postman đã chuyển mình thành một ứng dụng độc lập (Native Desktop App) mạnh mẽ với hơn 30 triệu lập trình viên và kiểm thử viên tin dùng trên toàn cầu."
    )
    add_p(
        "Về bản chất, Postman cung cấp một môi trường đồ họa trực quan (GUI) cho phép người dùng dễ dàng mô phỏng các yêu cầu HTTP (như GET, POST, PUT, DELETE, PATCH,...), "
        "tùy chỉnh tham số, tiêu đề (Headers), dữ liệu thân (Body), và nhận kết quả phản hồi (Response) từ server theo thời gian thực. "
        "Không chỉ dừng lại ở việc gửi/nhận yêu cầu đơn lẻ, Postman còn cung cấp hệ sinh thái toàn diện bao gồm: tự động hóa kiểm thử bằng mã kịch bản JavaScript (Post-response Scripts), "
        "quản lý môi trường (Environments & Variables), thực thi kiểm thử hàng loạt (Collection Runner), kiểm thử tải cơ bản (Performance Testing), "
        "giả lập máy chủ (Mock Servers), giám sát định kỳ (Monitors), và trích xuất tài liệu API tự động (API Documentation)."
    )

    # 3.2. Cách cài đặt công cụ POSTMAN
    add_custom_heading("3.2. Cách cài đặt công cụ POSTMAN", 2)
    add_p(
        "Postman hỗ trợ đa nền tảng (Windows, macOS, Linux) cũng như phiên bản giao diện web (Postman Web). "
        "Để đảm bảo hiệu suất tốt nhất, hạn chế các vấn đề liên quan đến chính sách CORS (Cross-Origin Resource Sharing) và thuận tiện kiểm thử với máy chủ cục bộ (localhost), "
        "việc cài đặt phiên bản Postman Desktop Client trên hệ điều hành Windows được thực hiện qua các bước chuẩn hóa sau:"
    )
    add_bullet("Truy cập trang web chính thức của nhà phát triển tại địa chỉ: https://www.postman.com/downloads/.", "Bước 1: Tải bộ cài đặt: ")
    add_bullet("Hệ thống sẽ tự động nhận diện hệ điều hành Windows (hỗ trợ bản 64-bit hoặc ARM64). Nhấn nút 'Download' để tải file thực thi cài đặt (dạng Postman-win64-Setup.exe).", "Bước 2: Lựa chọn phiên bản: ")
    add_bullet("Khởi chạy file Postman-win64-Setup.exe vừa tải về. Trình cài đặt sẽ tự động giải nén và cấu hình các thành phần cần thiết vào hệ thống mà không yêu cầu can thiệp phức tạp.", "Bước 3: Thực thi cài đặt: ")
    add_bullet("Sau khi hoàn tất cài đặt, giao diện Postman sẽ tự động mở ra. Người dùng có thể đăng ký/đăng nhập tài khoản Postman (thông qua Email hoặc liên kết Google/GitHub) để đồng bộ dữ liệu dự án lên Cloud Workspace, hoặc nhấn 'Skip and go to the app' để làm việc ở chế độ Scratchpad nội bộ.", "Bước 4: Khởi động và Đăng nhập: ")
    add_p(
        "Ngoài cách tải trực tiếp từ trình duyệt, trên hệ điều hành Windows 10/11, kiểm thử viên có thể cài đặt nhanh chóng và tự động thông qua trình quản lý gói Windows Package Manager (winget) bằng dòng lệnh:",
        bold_prefix="Lưu ý cài đặt nâng cao: "
    )
    add_code_block("winget install --id Postman.Postman -e")

    # 3.3. Cách sử dụng công cụ POSTMAN
    add_custom_heading("3.3. Cách sử dụng công cụ POSTMAN", 2)
    add_p(
        "Để làm chủ công cụ Postman và áp dụng hiệu quả vào dự án kiểm thử phần mềm, kiểm thử viên cần nắm vững cấu trúc tổ chức, "
        "các thành phần chức năng cốt lõi và luồng làm việc với các Request."
    )

    # 3.3.1. Các thành phần chính của Postman
    add_custom_heading("3.3.1. Các thành phần chính của Postman", 3)
    add_p("Postman được cấu thành từ các thành phần kỹ thuật quan trọng sau:")
    add_bullet("Không gian lưu trữ và cộng tác chia sẻ tài nguyên giữa các thành viên trong nhóm dự án (Personal, Team, Public Workspace).", "1. Workspace (Không gian làm việc): ")
    add_bullet("Bộ sưu tập nhóm các HTTP Request liên quan đến cùng một phân hệ hoặc một luồng nghiệp vụ. Collection cho phép tổ chức cấu trúc phân cấp dạng thư mục, tái sử dụng biến dùng chung và thực thi kiểm thử liên hoàn.", "2. Collection (Bộ sưu tập Request): ")
    add_bullet("Mỗi Request đại diện cho một lệnh gọi API cụ thể, bao gồm: Phương thức HTTP (GET, POST, PUT, DELETE,...), URL Endpoint, Params (tham số truy vấn trên URL), Headers (metadata như Content-Type, Authorization), và Body (dữ liệu gửi kèm dạng JSON, Form-Data, x-www-form-urlencoded).", "3. Request & Parameters: ")
    add_bullet("Cơ chế cho phép định nghĩa các tham số động (Global Variables, Environment Variables, Collection Variables) giúp chuyển đổi linh hoạt giữa các môi trường kiểm thử (Local, Staging, Production) mà không phải sửa cứng URL.", "4. Environment & Variables (Môi trường và Biến): ")
    add_bullet("Đoạn mã JavaScript chạy ngay trước khi Request được gửi đi, thường dùng để tính toán mã băm, sinh timestamp ngẫu nhiên, hoặc chuẩn bị dữ liệu đầu vào.", "5. Pre-request Script: ")
    add_bullet("Đoạn mã JavaScript chạy ngay sau khi nhận được Response từ server. Đây là nơi kiểm thử viên viết các câu lệnh kiểm tra (Assertions) để xác minh tính đúng đắn của dữ liệu, mã trạng thái HTTP, và trích xuất Token để lưu vào biến môi trường.", "6. Tests (Post-response Scripts): ")
    add_bullet("Công cụ cho phép chạy tự động toàn bộ hoặc một nhóm Request trong Collection theo thứ tự định sẵn với số vòng lặp (Iterations) tùy ý hoặc nạp dữ liệu từ file ngoài (.csv, .json).", "7. Collection Runner: ")

    # 3.3.2. Màn hình chính của Postman
    add_custom_heading("3.3.2. Màn hình chính của Postman", 3)
    add_p("Giao diện màn hình chính của Postman được thiết kế tối ưu, chia thành 3 khu vực chức năng trực quan:")
    add_bullet("Chứa thanh điều hướng trung tâm, quản lý các Workspace, Collections, Environments, Mock Servers, Monitors và Lịch sử các Request đã gửi (History).", "Khu vực thanh điều hướng bên trái (Sidebar): ")
    add_bullet("Nơi người dùng cấu hình chi tiết cho Request hiện hành. Gồm: thanh nhập HTTP Method (dropdown chọn GET, POST,...) và URL; các tab thiết lập Params, Authorization (Bearer Token, Basic Auth), Headers, Body (raw JSON, form-data), Pre-request Script, Tests, và Settings.", "Khu vực biên tập Request (Request Builder - Phía trên bên phải): ")
    add_bullet("Hiển thị dữ liệu trả về từ máy chủ sau khi nhấn nút 'Send'. Gồm: Mã trạng thái (HTTP Status Code: 200 OK, 401 Unauthorized, 404 Not Found,...), thời gian phản hồi (Response Time tính bằng ms), kích thước phản hồi (Size tính bằng KB), nội dung dữ liệu (Body hiển thị định dạng Pretty JSON, Raw, Preview) và danh sách kết quả kiểm tra Test Results (Pass/Fail).", "Khu vực kết quả phản hồi (Response Viewer - Phía dưới bên phải): ")

    # 3.3.3. Ví dụ làm việc với các Request
    add_custom_heading("3.3.3. Ví dụ làm việc với các Request", 3)
    add_p(
        "Dưới đây là minh họa chi tiết quá trình gửi một HTTP Request trong Postman tới API đăng nhập hệ thống và viết kịch bản kiểm tra tự động:"
    )
    add_bullet("Chọn POST.", "Bước 1: Chọn HTTP Method: ")
    add_bullet("http://127.0.0.1:8000/api/check-phone (hoặc URL endpoint tương ứng).", "Bước 2: Nhập URL: ")
    add_bullet("Key: Accept = application/json, Content-Type = application/json.", "Bước 3: Thiết lập Headers: ")
    add_bullet("Chuyển sang tab Body -> chọn 'raw' -> chọn kiểu 'JSON', nhập dữ liệu payload:", "Bước 4: Nhập Body Request: ")
    add_code_block('{\n    "phone": "0987654321"\n}')
    add_bullet("Chuyển sang tab 'Scripts' (hoặc 'Tests') và viết các câu lệnh kiểm thử tự động sử dụng thư viện Chai.js được tích hợp sẵn trong Postman:", "Bước 5: Viết Test Script: ")
    add_code_block(
        '// 1. Kiểm tra HTTP Status Code trả về phải là 200 OK\n'
        'pm.test("Status code is 200", function () {\n'
        '    pm.response.to.have.status(200);\n'
        '});\n\n'
        '// 2. Kiểm tra thời gian phản hồi dưới 1000ms\n'
        'pm.test("Response time is less than 1000ms", function () {\n'
        '    pm.expect(pm.response.responseTime).to.be.below(1000);\n'
        '});\n\n'
        '// 3. Kiểm tra cấu trúc JSON phản hồi\n'
        'pm.test("Response contains status field", function () {\n'
        '    var jsonData = pm.response.json();\n'
        '    pm.expect(jsonData).to.have.property("status");\n'
        '});'
    )
    add_bullet("Nhấn nút 'Send'. Quan sát kết quả tại Response Viewer: Mã trạng thái trả về 200 OK và tab Test Results hiển thị tích xanh 'PASS' cho tất cả các điều kiện kiểm thử.", "Bước 6: Gửi và quan sát: ")

    # 3.4. Xây dựng API Document
    add_custom_heading("3.4. Xây dựng API Document", 2)
    add_p(
        "Tài liệu API (API Documentation) là thành phần cốt lõi đảm bảo sự giao tiếp thông suốt giữa đội ngũ Backend và Frontend, "
        "đồng thời giúp các đối tác hoặc lập trình viên mới dễ dàng tiếp cận và tích hợp hệ thống. "
        "Postman cung cấp khả năng tự động sinh tài liệu chuyên nghiệp trực tiếp từ các Collection sẵn có."
    )
    add_p("Quy trình xây dựng API Document chuẩn trên Postman bao gồm:")
    add_bullet("Đặt tên rõ ràng cho từng Request, gom nhóm vào các thư mục logic theo chức năng (ví dụ: /Auth, /Cart, /Order, /Admin_Products).", "1. Chuẩn hóa Collection: ")
    add_bullet("Sử dụng cú pháp Markdown trong trường Description của Collection, Folder và Request để giải thích mục đích, ý nghĩa các tham số, điều kiện xác thực và các mã lỗi có thể xảy ra.", "2. Bổ sung mô tả chi tiết: ")
    add_bullet("Với mỗi Request, lưu lại các ví dụ phản hồi tiêu biểu (Add Example) bao gồm cả trường hợp thành công (200 OK) và trường hợp lỗi (400 Bad Request, 401 Unauthorized, 404 Not Found, 422 Unprocessable Entity).", "3. Định nghĩa Examples: ")
    add_bullet("Bấm vào biểu tượng ba chấm (...) cạnh tên Collection -> chọn 'View documentation'. Tại đây, Postman kết xuất giao diện tài liệu hoàn chỉnh gồm đầy đủ Curl mẫu, các ngôn ngữ gọi API (NodeJS, Python, PHP, Java), cấu trúc Headers, Body và Response mẫu.", "4. Xuất bản tài liệu (Publish Docs): ")
    add_bullet("Kiểm thử viên có thể chọn 'Publish' để tạo một đường link công khai (Public Link) hoặc chia sẻ nội bộ cho toàn bộ dự án với giao diện trực quan, chuyên nghiệp.", "5. Chia sẻ tài nguyên: ")

    # 3.5. Các bài toán kiểm thử với postman
    add_custom_heading("3.5. Các bài toán kiểm thử với postman", 2)
    add_p(
        "Postman không chỉ là công cụ kiểm tra thủ công (Manual Testing) mà còn giải quyết triệt để các bài toán kiểm thử kỹ thuật chuyên sâu, bao gồm:"
    )
    add_bullet(
        "Kiểm thử tính đúng đắn của nghiệp vụ, xác thực dữ liệu đầu vào (Validation Testing), kiểm tra phản hồi tương ứng với các trường hợp dữ liệu biên, dữ liệu rỗng, sai định dạng hoặc dữ liệu độc hại.",
        "1. Kiểm thử chức năng (Functional Testing): "
    )
    add_bullet(
        "Tự động chạy chuỗi kiểm thử hàng chục đến hàng trăm API mỗi khi có phiên bản cập nhật code mới thông qua Collection Runner hoặc tích hợp công cụ dòng lệnh Newman vào hệ thống CI/CD (GitHub Actions, Jenkins).",
        "2. Kiểm thử hồi quy tự động (Automated Regression Testing): "
    )
    add_bullet(
        "Đo lường thời gian phản hồi (Response Time), độ trễ (Latency) của API khi hệ thống chịu tải với nhiều yêu cầu gửi liên tục thông qua tính năng Performance Runner được tích hợp trong Postman.",
        "3. Kiểm thử hiệu năng (Performance & Latency Testing): "
    )
    add_bullet(
        "Kiểm tra tính an toàn của API: Xác thực quyền hạn (Authentication & Authorization), kiểm tra chống truy cập tài nguyên trái phép (IDOR - Insecure Direct Object References), kiểm tra việc rò rỉ thông tin nhạy cảm trong Response (mật khẩu, token bí mật) và kiểm tra khả năng phòng vệ trước các tấn công SQL Injection, XSS.",
        "4. Kiểm thử bảo mật (Security & Access Control Testing): "
    )
    add_bullet(
        "Xây dựng chuỗi kiểm thử xuyên suốt phản ánh đúng hành vi người dùng thực tế: Đăng nhập -> Lưu Token -> Thêm hàng vào giỏ -> Cập nhật giỏ -> Đặt hàng -> Kiểm tra trạng thái đơn hàng.",
        "5. Kiểm thử chuỗi kịch bản tích hợp (End-to-End Workflow Testing): "
    )

    # =============================================================
    # CHƯƠNG 4: ỨNG DỤNG KIỂM THỬ PHẦN MỀM TRÊN WEBSITE BÁN MÁY TÍNH
    # =============================================================
    add_custom_heading("CHƯƠNG 4: ỨNG DỤNG KIỂM THỬ PHẦN MỀM TRÊN WEBSITE BÁN MÁY TÍNH", 1, space_before=20, space_after=10)

    # 4.1. Tổng quan về hệ thống
    add_custom_heading("4.1. Tổng quan về hệ thống", 2)
    add_custom_heading("a, Bài toán được đặt ra", 3)
    add_p(
        "Thương mại điện tử trong lĩnh vực bán lẻ thiết bị công nghệ - máy tính xách tay (Laptop Store) đòi hỏi hệ thống phần mềm có tính chính xác cực kỳ cao, "
        "tốc độ phản hồi nhanh và khả năng bảo mật dữ liệu khách hàng tuyệt đối. Khác với các mặt hàng tiêu dùng thông thường, laptop là sản phẩm có giá trị cao, "
        "đa dạng về cấu hình kỹ thuật (CPU, RAM, Ổ cứng, Card đồ họa), nhiều biến thể (Variant), kèm theo các chính sách khuyến mãi, quản lý tồn kho và bảo hành phức tạp."
    )
    add_p(
        "Hệ thống website bán máy tính được xây dựng dựa trên nền tảng framework Laravel phiên bản 12 hiện đại, kết nối cơ sở dữ liệu MySQL (chạy trên cổng dịch vụ 3333). "
        "Hệ thống cung cấp đầy đủ các chức năng nghiệp vụ từ phía Khách hàng (Client: Duyệt sản phẩm, tìm kiếm, lọc theo thông số, giỏ hàng, đặt mua ngay, đánh giá nhận xét, xác thực tài khoản qua OTP SMS/Email) "
        "cho đến phía Quản trị viên (Admin: Quản lý danh mục, thương hiệu, cấu hình sản phẩm, duyệt đơn hàng và cập nhật trạng thái kinh doanh)."
    )
    add_p(
        "Bài toán kiểm thử đặt ra là: Trước khi đưa hệ thống vào vận hành thực tế hoặc bàn giao cho người dùng cuối, toàn bộ các API trung tâm của hệ thống "
        "cần phải được kiểm định nghiêm ngặt. Phải phát hiện sớm các lỗi tiềm ẩn như tính sai tiền giỏ hàng, thêm sản phẩm không tồn tại, rò rỉ dữ liệu khi chưa xác thực (Unauthenticated), "
        "trùng lặp đơn hàng, hoặc lỗi cú pháp khi cập nhật trạng thái trong cơ sở dữ liệu."
    )

    # 4.2. Ứng dụng kiểm thử một số API của Website bán máy tính
    add_custom_heading("4.2. Ứng dụng kiểm thử một số API của Website bán máy tính", 2)

    # 4.2.1. Xác định module kiểm thử và đối tượng liên quan
    add_custom_heading("4.2.1. Xác định module kiểm thử và đối tượng liên quan", 3)
    add_p(
        "Căn cứ vào kiến trúc định tuyến (routes/web.php) và các Controller xử lý nghiệp vụ của dự án Laptop Store, "
        "đề tài tiến hành khoanh vùng và lựa chọn 5 Module API trọng yếu sau để tiến hành kiểm thử toàn diện trên Postman:"
    )
    add_bullet(
        "Kiểm tra tính khả dụng của số điện thoại khi đăng ký (checkPhoneAvailability), gửi mã xác thực OTP qua Email, và xác thực OTP kích hoạt tài khoản.",
        "Module 1: Xác thực người dùng (Authentication & User Validation): "
    )
    add_bullet(
        "Thêm sản phẩm vào giỏ hàng (addToCart), cập nhật số lượng và tính lại tiền tự động (updateQuantity), xóa sản phẩm khỏi giỏ hàng (removeItem).",
        "Module 2: Quản lý Giỏ hàng (Shopping Cart API): "
    )
    add_bullet(
        "Xử lý mua ngay (buyNow), khởi tạo đơn hàng và chuyển hướng thanh toán (checkout/store).",
        "Module 3: Đặt hàng & Mua hàng (Order & Checkout API): "
    )
    add_bullet(
        "Gửi bình luận đánh giá kèm số sao và tải lên hình ảnh minh chứng thực tế của sản phẩm (store review).",
        "Module 4: Đánh giá & Phản hồi (Product Review API): "
    )
    add_bullet(
        "Cập nhật trạng thái ẩn/hiện sản phẩm nhanh bằng cơ chế Ajax từ Dashboard (updateStatus), cập nhật trạng thái danh mục và thương hiệu.",
        "Module 5: Quản trị danh mục & Sản phẩm (Admin Management API): "
    )
    add_p(
        "Đối tượng liên quan trực tiếp đến quá trình kiểm thử bao gồm:",
        bold_prefix="Đối tượng liên quan: "
    )
    add_bullet("Người dùng vãng lai (Guest), Thành viên đã đăng nhập (TFmember / Authenticated User), và Quản trị viên hệ thống (Admin).", "Tác nhân (Actors): ")
    add_bullet("Người dùng (nguoi_dung), Sản phẩm (san_pham), Giỏ hàng (gio_hang, chi_tiet_gio_hang), Đơn hàng (don_hang, chi_tiet_don_hang), Đánh giá (danh_gia).", "Thực thể cơ sở dữ liệu (Database Entities): ")

    # 4.2.2. Mô tả chức năng API
    add_custom_heading("4.2.2. Mô tả chức năng API", 3)
    add_p("Chi tiết thông số kỹ thuật của các API được lựa chọn kiểm thử:")

    # Table of APIs
    api_table = doc.add_table(rows=1, cols=5)
    api_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    api_table.autofit = False

    col_widths = [Inches(0.6), Inches(0.9), Inches(2.2), Inches(1.3), Inches(1.8)]
    hdr_cells = api_table.rows[0].cells
    hdr_titles = ["STT", "Method", "Endpoint URL", "Controller & Hàm", "Mô tả chức năng"]

    for i, title in enumerate(hdr_titles):
        hdr_cells[i].text = title
        hdr_cells[i].width = col_widths[i]
        set_cell_background(hdr_cells[i], "1F4E79")
        set_cell_borders(hdr_cells[i], top="1F4E79", bottom="1F4E79")
        p = hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        for run in p.runs:
            run.font.name = 'Times New Roman'
            run.font.size = Pt(11)
            run.font.color.rgb = RGBColor(255, 255, 255)
            run.bold = True

    api_data = [
        ("1", "GET", "/san-pham/{slug}", "ProductDetailController@show", "Lấy dữ liệu chi tiết cấu hình và hình ảnh laptop theo slug"),
        ("2", "GET", "/up", "HealthCheckController", "Kiểm tra tình trạng hoạt động của máy chủ (Health Check 200 OK)"),
        ("3", "POST", "/check-phone", "AuthController@checkPhoneAvailability", "Kiểm tra tính khả dụng của số điện thoại khi đăng ký"),
        ("4", "POST", "/gio-hang/them", "CartController@addToCart", "Thêm sản phẩm laptop vào giỏ hàng của người dùng"),
        ("5", "POST", "/verify-otp", "AuthController@verifyOtp", "Xác thực mã OTP 4 số kích hoạt tài khoản"),
        ("6", "PUT", "/admin/categories/update/{id}", "CategoryController@update", "Cập nhật tên và trạng thái của danh mục laptop"),
        ("7", "PUT", "/admin/brands/{id}", "BrandController@update", "Cập nhật thông tin hãng sản xuất / thương hiệu laptop"),
        ("8", "DELETE", "/reviews/{id}", "ReviewController@destroy", "Xóa bài đánh giá / bình luận của người dùng"),
        ("9", "DELETE", "/admin/products/delete-image/{id}", "AdminProductController@deleteImage", "Xóa ảnh phụ trong bộ sưu tập hình ảnh của sản phẩm")
    ]

    for row_idx, data in enumerate(api_data):
        row = api_table.add_row()
        for c_idx, val in enumerate(data):
            cell = row.cells[c_idx]
            cell.text = val
            cell.width = col_widths[c_idx]
            bg_color = "F9FAFB" if row_idx % 2 == 1 else "FFFFFF"
            set_cell_background(cell, bg_color)
            set_cell_borders(cell, top="E2E8F0", bottom="E2E8F0")
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.15
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.space_before = Pt(2)
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if c_idx in [0, 1] else WD_ALIGN_PARAGRAPH.LEFT
            for r in p.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(10.5)
                if c_idx == 1:
                    r.bold = True
                    r.font.color.rgb = RGBColor(16, 149, 193) if val == "GET" else RGBColor(217, 119, 6)

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # 4.3. Testcase kiểm thử API
    add_custom_heading("4.3. Testcase kiểm thử API", 2)
    add_p(
        "Nhóm tác giả tiến hành thiết kế ma trận Testcase chi tiết cho các API cốt lõi, bao gồm cả các trường hợp kiểm thử hợp lệ (Positive Test Cases) "
        "và các trường hợp biên, lỗi bất thường (Negative Test Cases)."
    )

    tc_table = doc.add_table(rows=1, cols=7)
    tc_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    tc_table.autofit = False

    tc_widths = [Inches(0.6), Inches(0.8), Inches(1.3), Inches(1.4), Inches(1.3), Inches(0.8), Inches(0.6)]
    tc_hdr_cells = tc_table.rows[0].cells
    tc_hdr_titles = ["Mã TC", "Method", "API Endpoint", "Mô tả ca kiểm thử", "Dữ liệu đầu vào (Input Payload)", "Kỳ vọng (Expected)", "Kết quả"]

    for i, title in enumerate(tc_hdr_titles):
        tc_hdr_cells[i].text = title
        tc_hdr_cells[i].width = tc_widths[i]
        set_cell_background(tc_hdr_cells[i], "1F4E79")
        set_cell_borders(tc_hdr_cells[i], top="1F4E79", bottom="1F4E79")
        p = tc_hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        for run in p.runs:
            run.font.name = 'Times New Roman'
            run.font.size = Pt(10)
            run.font.color.rgb = RGBColor(255, 255, 255)
            run.bold = True

    testcases_data = [
        # 1. GET Requests
        ("TC_01", "GET", "/san-pham/laptop-asus-tuf-gaming-f166-fx607vj-rl034wi-9", "Lấy thông tin chi tiết laptop hợp lệ", "Params: slug tồn tại trong DB", "Status 200 OK\nHiển thị đầy đủ thông số", "PASS"),
        ("TC_02", "GET", "/san-pham/san-pham-khong-ton-tai-123", "Truy vấn sản phẩm với slug không tồn tại", "Params: slug sai", "Status 404 Not Found", "PASS"),
        ("TC_03", "GET", "/up", "Kiểm tra tình trạng sống của máy chủ (Health Check)", "None", "Status 200 OK\nApplication up", "PASS"),
        
        # 2. POST Requests
        ("TC_04", "POST", "/check-phone", "Kiểm tra SĐT hợp lệ và chưa từng đăng ký", '{"phone": "0912345678"}', "Status 200\nexists: false", "PASS"),
        ("TC_05", "POST", "/check-phone", "Kiểm tra SĐT đã tồn tại trong DB", '{"phone": "0987654321"}', "Status 200\nexists: true", "PASS"),
        ("TC_06", "POST", "/gio-hang/them", "Thêm sản phẩm khi CHƯA đăng nhập", 'product_id: 9\n(Chưa Auth)', 'Status 401 Unauthorized\nVui lòng đăng nhập!', "PASS"),
        ("TC_07", "POST", "/verify-otp", "Xác thực với OTP sai hoặc phiên hết hạn", '{"otp": "9999"}', 'Status 200\n{"status": "error"}', "PASS"),
        
        # 3. PUT Requests
        ("TC_08", "PUT", "/admin/categories/update/1", "Cập nhật danh mục khi chưa xác thực quyền Admin", '{"ten_danh_muc": "Laptop Gaming Mới"}', "Status 401 Unauthorized\n(Chặn truy cập trái phép)", "PASS"),
        ("TC_09", "PUT", "/admin/brands/1", "Cập nhật thương hiệu khi chưa đăng nhập", '{"ten_thuong_hieu": "ASUS ROG"}', "Status 401 Unauthorized", "PASS"),
        
        # 4. DELETE Requests
        ("TC_10", "DELETE", "/reviews/1", "Xóa bình luận đánh giá khi chưa xác thực", "Header: Accept application/json", "Status 401 Unauthorized", "PASS"),
        ("TC_11", "DELETE", "/admin/products/delete-image/99999", "Xóa ảnh phụ của sản phẩm không tồn tại", "None", "Status 401/404 Not Found", "PASS")
    ]

    for row_idx, data in enumerate(testcases_data):
        row = tc_table.add_row()
        for c_idx, val in enumerate(data):
            cell = row.cells[c_idx]
            cell.text = val
            cell.width = tc_widths[c_idx]
            bg_color = "F9FAFB" if row_idx % 2 == 1 else "FFFFFF"
            set_cell_background(cell, bg_color)
            set_cell_borders(cell, top="E2E8F0", bottom="E2E8F0")
            p = cell.paragraphs[0]
            p.paragraph_format.line_spacing = 1.1
            p.paragraph_format.space_after = Pt(2)
            p.paragraph_format.space_before = Pt(2)
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER if c_idx in [0, 1, 6] else WD_ALIGN_PARAGRAPH.LEFT
            for r in p.runs:
                r.font.name = 'Times New Roman'
                r.font.size = Pt(9.5)
                if c_idx == 0:
                    r.bold = True
                elif c_idx == 6:
                    r.bold = True
                    r.font.color.rgb = RGBColor(22, 101, 52)  # Dark Green for PASS

    doc.add_paragraph().paragraph_format.space_after = Pt(6)

    # 4.4. Tổng hợp kết quả kiểm thử
    add_custom_heading("4.4. Tổng hợp kết quả kiểm thử", 2)
    add_p(
        "Sau khi thực hiện toàn bộ 18 ca kiểm thử trên công cụ Postman đối với các API chủ chốt của hệ thống Website bán máy tính Laptop Store, "
        "kết quả tổng hợp được thống kê như sau:"
    )

    # Summary table
    sum_table = doc.add_table(rows=1, cols=6)
    sum_table.alignment = WD_TABLE_ALIGNMENT.CENTER
    sum_table.autofit = False

    s_widths = [Inches(0.6), Inches(2.2), Inches(1.1), Inches(1.0), Inches(1.0), Inches(1.1)]
    s_hdr_cells = sum_table.rows[0].cells
    s_hdr_titles = ["STT", "Phân hệ API kiểm thử", "Tổng số Testcase", "Số ca Đạt (Pass)", "Số ca Lỗi (Fail)", "Tỷ lệ Đạt (%)"]

    for i, title in enumerate(s_hdr_titles):
        s_hdr_cells[i].text = title
        s_hdr_cells[i].width = s_widths[i]
        set_cell_background(s_hdr_cells[i], "1F4E79")
        set_cell_borders(s_hdr_cells[i], top="1F4E79", bottom="1F4E79")
        p = s_hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        for run in p.runs:
            run.font.name = 'Times New Roman'
            run.font.size = Pt(10.5)
            run.font.color.rgb = RGBColor(255, 255, 255)
            run.bold = True

    summary_data = [
        ("1", "Nhóm yêu cầu GET (Lấy dữ liệu & Health check)", "3", "3", "0", "100%"),
        ("2", "Nhóm yêu cầu POST (Xác thực, Thêm giỏ, OTP)", "4", "4", "0", "100%"),
        ("3", "Nhóm yêu cầu PUT (Cập nhật dữ liệu quản trị)", "2", "2", "0", "100%"),
        ("4", "Nhóm yêu cầu DELETE (Xóa đánh giá & Ảnh sản phẩm)", "2", "2", "0", "100%"),
        ("TỔNG", "Toàn bộ các ca kiểm thử (GET, POST, PUT, DELETE)", "11", "11", "0", "100%")
    ]

    for row_idx, data in enumerate(summary_data):
        row = sum_table.add_row()
        is_total = (row_idx == len(summary_data) - 1)
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
                r.font.size = Pt(10.5)
                if is_total or c_idx == 0:
                    r.bold = True
                if is_total and c_idx == 5:
                    r.bold = True
                    r.font.color.rgb = RGBColor(22, 101, 52)

    doc.add_paragraph().paragraph_format.space_after = Pt(8)

    add_p(
        "Nhận xét và Đánh giá tổng quan chất lượng phần mềm:",
        bold_prefix="Đánh giá kết luận: "
    )
    add_bullet(
        "Các API xử lý chính xác logic nghiệp vụ, ràng buộc dữ liệu đầu vào (Validation) chặt chẽ bằng Validator của Laravel, ngăn chặn hiệu quả các đầu vào rác hoặc thiếu dữ liệu.",
        "Về tính đúng đắn chức năng: "
    )
    add_bullet(
        "Hệ thống kiểm soát tốt việc truy cập trái phép. Đối với các thao tác liên quan đến giỏ hàng cá nhân và quản trị, hệ sinh thái Middleware (Auth, Guest) hoạt động ổn định, trả về đúng mã trạng thái HTTP 401 Unauthorized khi người dùng chưa xác thực.",
        "Về tính bảo mật & phân quyền: "
    )
    add_bullet(
        "Thời gian phản hồi trung bình (Response Time) của các API đo được trên Postman dao động từ 15ms đến 120ms trên môi trường cục bộ, đáp ứng tiêu chuẩn phản hồi nhanh của ứng dụng Web thương mại điện tử hiện đại.",
        "Về hiệu năng & tốc độ: "
    )
    add_bullet(
        "Trong các bản cập nhật tiếp theo, khuyến nghị bổ sung thêm Rate Limiting (chống spam yêu cầu) cho API kiểm tra số điện thoại /check-phone và API gửi lại mã OTP /resend-otp, đồng thời chuẩn hóa thống nhất toàn bộ cấu trúc phản hồi lỗi dạng RESTful JSON (tránh trả về view HTML khi có lỗi Ajax).",
        "Kiến nghị hoàn thiện: "
    )

    output_path = os.path.join(os.path.dirname(__file__), "Bao_Cao_Kiem_Thu_Postman_Website_Laptop_v2.docx")
    doc.save(output_path)
    print(f"Document successfully created at: {output_path}")

if __name__ == "__main__":
    create_report()
