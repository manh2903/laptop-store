var uploadedImages = [];
var uploadedFileNames = [];
$(document).ready(function () {
    $("#file").on("change", function () {
        var file = this.files[0];
        if (!file) return;

        var formData = new FormData();
        formData.append("file", file);

        $("#thumbnailStatus").text("Đang upload...").css("color", "orange");

        $.ajax({
            url: "/upload",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#thumbnailStatus")
                        .text("✓ Upload thành công")
                        .css("color", "green");
                    var img =
                        '<img src="' +
                        response.url +
                        '" style="max-width: 200px;">';
                    $("#thumbnailPreview").html(img).show();
                    $("#thumbnailPath").val(response.path);
                } else {
                    $("#thumbnailStatus")
                        .text("✗ Lỗi upload")
                        .css("color", "red");
                }
            },
            error: function () {
                $("#thumbnailStatus").text("✗ Lỗi server").css("color", "red");
            },
        });
    });

    $(document).on("click", "#removeThumbnail", function () {
        $("#thumbnailPreview").hide().html("");
        $("#thumbnailPath").val("");
        $("#file").val("");
        $("#thumbnailStatus").text("Chưa chọn ảnh").css("color", "#666");
    });
    // upload ảnh
    $("#uploadImages").on("change", function () {
        var files = this.files;
        if (files.length === 0) return;

        var formData = new FormData();
        var hasNewFile = false; // Cờ kiểm tra xem có file mới hợp lệ không
        var newFilesCount = 0;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            if (uploadedFileNames.includes(file.name)) {
                console.log("Bỏ qua file trùng: " + file.name);
                continue;
            }
            formData.append("files[]", file);

            uploadedFileNames.push(file.name);
            hasNewFile = true;
            newFilesCount++;
        }
        if (!hasNewFile) {
            alert("Các ảnh bạn chọn đã tồn tại trong danh sách!");
            $(this).val("");
            return;
        }

        $("#fileCount")
            .text("Đang upload " + newFilesCount + " ảnh...")
            .css("color", "orange");

        $.ajax({
            url: "/upload-multiple",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success && response.data) {
                    response.data.forEach(function (imgData) {
                        uploadedImages.push(imgData.path);
                        var imgHtml =
                            '<div class="img-preview-item" style="width: 100px; height: 100px; position: relative; border-radius: 5px; overflow: hidden; border: 1px solid #e5e7eb; float: left; margin-right: 10px; margin-bottom: 10px;">' +
                            '<img src="' +
                            imgData.url +
                            '" style="width: 100%; height: 100%; object-fit: cover;">' +
                            '<button type="button" class="remove-product-img" data-path="' +
                            imgData.path +
                            '" style="position: absolute; top: 2px; right: 2px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">×</button>' +
                            "</div>";

                        $("#imagePreviewContainer").append(imgHtml);
                    });

                    $("#productImages").val(JSON.stringify(uploadedImages));

                    $("#fileCount")
                        .text("✓ Đã thêm " + newFilesCount + " ảnh mới")
                        .css("color", "green");
                } else {
                    $("#fileCount").text("✗ Có lỗi xảy ra").css("color", "red");
                }

                $("#uploadImages").val("");
            },
            error: function () {
                $("#fileCount")
                    .text("✗ Lỗi kết nối Server")
                    .css("color", "red");
            },
        });
    });

    // nếu xóa ảnh thì cần phải f5 đển thêm lại ảnh cũ
    $(document).on("click", ".remove-product-img", function () {
        var path = $(this).data("path");

        uploadedImages = uploadedImages.filter(function (item) {
            return item !== path;
        });

        $("#productImages").val(JSON.stringify(uploadedImages));

        $(this).closest(".img-preview-item").remove();

        if (uploadedImages.length === 0) {
            $("#fileCount").text("Chưa chọn ảnh nào").css("color", "#666");
        } else {
            $("#fileCount")
                .text("Đang có " + uploadedImages.length + " ảnh")
                .css("color", "green");
        }
    });
});
