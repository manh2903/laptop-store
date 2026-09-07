$(document).ready(function () {
    // Cấu hình Ajax Setup (Giữ nguyên)
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // ==================================================
    // 1. XỬ LÝ XÓA (DELETE)
    // ==================================================
    $(document).on("click", ".btn-delete-item", function (e) {
        e.preventDefault();

        let url = $(this).data("url");
        // Slider ko có count thì mặc định là 0
        let count = $(this).data("count") || 0;
        let $row = $(this).closest("tr");

        // --- ĐOẠN NÀY MÌNH CẬP NHẬT THÊM CHO BẠN ---
        let itemName = "Mục"; // Mặc định
        if (url.includes("categories")) itemName = "Danh mục";
        else if (url.includes("brands")) itemName = "Thương hiệu";
        else if (url.includes("products")) itemName = "Sản phẩm";
        else if (url.includes("slider"))
            itemName = "Slider"; // <--- Thêm dòng này
        else if (url.includes("users")) itemName = "Người dùng";
        // --------------------------------------------

        let text =
            count > 0
                ? `${itemName} này đang chứa ${count} sản phẩm. Dữ liệu sẽ không thể khôi phục!`
                : `Bạn có chắc muốn xóa ${itemName} này không? Dữ liệu sẽ mất vĩnh viễn!`;

        Swal.fire({
            title: "Cảnh báo!",
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Đồng ý xóa!",
            cancelButtonText: "Hủy bỏ",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    success: function (res) {
                        if (res.status === "success") {
                            Swal.fire("Đã xóa!", res.message, "success");
                            $row.fadeOut(500, function () {
                                $(this).remove();
                            });
                        } else {
                            Swal.fire("Lỗi!", res.message, "error");
                        }
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON
                            ? xhr.responseJSON.message
                            : "Có lỗi hệ thống xảy ra!";
                        Swal.fire("Lỗi!", msg, "error");
                    },
                });
            }
        });
    });

    // ==================================================
    // 2. XỬ LÝ ĐỔI TRẠNG THÁI (Giữ nguyên code cũ của bạn)
    // ==================================================
    $(document).on("change", ".btn-toggle-status", function (e) {
        let $input = $(this);
        let url = $input.data("url");
        let newState = $input.is(":checked");

        $.ajax({
            url: url,
            type: "PUT",
            data: {},
            success: function (res) {
                if (res.status === "success") {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon: "success",
                        title: res.message,
                    });
                } else {
                    $input.prop("checked", !newState);
                    Swal.fire("Lỗi!", res.message, "error");
                }
            },
            error: function () {
                $input.prop("checked", !newState);
                Swal.fire("Lỗi!", "Không thể kết nối server", "error");
            },
        });
    });
});
