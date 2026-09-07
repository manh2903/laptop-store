<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('thong_so_ky_thuat', function (Blueprint $table) {
        // CPU & Đồ họa
        $table->string('cong_nghe_cpu')->nullable(); // VD: Apple M2
        $table->string('so_nhan')->nullable();       // VD: 8 nhân
        $table->string('loai_card_do_hoa')->nullable(); // VD: 8 nhân GPU

        // Màn hình (Tách nhỏ để hiển thị chi tiết)
        $table->string('kich_thuoc_man_hinh')->nullable(); // VD: 13.6 inch
        $table->string('cong_nghe_man_hinh')->nullable();  // VD: Liquid Retina
        $table->string('do_phan_giai')->nullable();        // VD: 2560 x 1664 pixels
        $table->string('tam_nen')->nullable();             // VD: IPS
        
        // Âm thanh & Khác
        $table->string('cong_nghe_am_thanh')->nullable();
        $table->string('cong_giao_tiep')->nullable();      // VD: Thunderbolt 3...
        $table->string('he_dieu_hanh')->nullable();        // VD: macOS
        
        // Kích thước & Chất liệu
        $table->string('chat_lieu')->nullable();           // VD: Vỏ kim loại
        $table->string('kich_thuoc_tong_the')->nullable(); // VD: Dày 1.13cm...
    });

    // Thêm cột Video cho bảng Sản phẩm
    Schema::table('san_pham', function (Blueprint $table) {
        $table->string('video_url')->nullable(); // Link video hoặc file upload
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_specs', function (Blueprint $table) {
            //
        });
    }
};
