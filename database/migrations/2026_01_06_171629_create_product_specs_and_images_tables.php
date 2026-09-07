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
    // 1. Bảng Thông số kỹ thuật
    Schema::create('thong_so_ky_thuat', function (Blueprint $table) {
        $table->id();

        // --- SỬA Ở ĐÂY ---
        // Dùng integer() thường (không có unsigned) để khớp với int(11) của bảng san_pham
        $table->integer('id_san_pham'); 
        // -----------------

        $table->string('cpu')->nullable();
        $table->string('ram')->nullable();
        $table->string('o_cung')->nullable();
        $table->string('gpu')->nullable();
        $table->string('man_hinh')->nullable();
        $table->string('pin')->nullable();
        $table->string('trong_luong')->nullable();
        $table->text('thong_tin_khac')->nullable();
        $table->timestamps();

        // Khai báo khóa ngoại
        $table->foreign('id_san_pham')->references('id')->on('san_pham')->onDelete('cascade');
    });

    // 2. Bảng Hình ảnh sản phẩm
    Schema::create('hinh_anh_san_pham', function (Blueprint $table) {
        $table->id();

        // --- SỬA TƯƠNG TỰ ---
        $table->integer('id_san_pham'); 
        // --------------------

        $table->string('duong_dan_anh');
        $table->timestamps();

        $table->foreign('id_san_pham')->references('id')->on('san_pham')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specs_and_images_tables');
    }
};
