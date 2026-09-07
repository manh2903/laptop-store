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
    Schema::create('bien_the_san_pham', function (Blueprint $table) {
        $table->id(); 
        
        // --- SỬA LẠI: Dùng integer thường (có dấu) để khớp với bảng cha ---
        $table->integer('id_san_pham'); 
        
        // Tạo khóa ngoại
        $table->foreign('id_san_pham')
              ->references('id')
              ->on('san_pham')
              ->onDelete('cascade');

        $table->string('ten_mau');
        $table->decimal('gia_ban', 15, 0); 
        $table->string('anh_mau')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bien_the_san_pham');
    }
};
