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
    Schema::create('cau_hinh_san_pham', function (Blueprint $table) {
        $table->id();
        
        // --- SỬA QUAN TRỌNG: BỎ UNSIGNED ĐỂ KHỚP VỚI BẢNG CHA ---
        // Vì bảng san_pham của bạn dùng int(11) (có dấu), nên ở đây cũng phải dùng integer (có dấu)
        $table->integer('id_san_pham'); 
        
        // Tạo khóa ngoại
        $table->foreign('id_san_pham')
              ->references('id')
              ->on('san_pham') // Đảm bảo tên bảng cha là 'san_pham'
              ->onDelete('cascade');

        $table->string('loai_cau_hinh'); // 'ram', 'ssd', 'chip'
        $table->string('ten_cau_hinh');  // '16GB', '512GB'
        $table->decimal('gia_them', 15, 0)->default(0); 
        $table->boolean('la_mac_dinh')->default(false); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_hinh_san_pham');
    }
};
