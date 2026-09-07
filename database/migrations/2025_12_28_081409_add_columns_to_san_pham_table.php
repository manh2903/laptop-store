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
    Schema::table('san_pham', function (Blueprint $table) {
        // Thêm các cột quản lý hiển thị mà bạn chưa có
        $table->boolean('is_flash_sale')->default(0)->after('trang_thai'); // Hiện ở Section 1
        $table->boolean('tra_gop_0_phan_tram')->default(0)->after('is_flash_sale'); // Badge trả góp
        
        // Thêm các cột thông tin ưu đãi cho Section 2
        $table->string('uu_dai_student')->nullable()->after('mo_ta'); 
        $table->string('uu_dai_khac')->nullable()->after('uu_dai_student');
        
        $table->float('danh_gia')->default(5.0)->after('uu_dai_khac'); // Số sao
    });
}

public function down()
{
    Schema::table('san_pham', function (Blueprint $table) {
        $table->dropColumn(['is_flash_sale', 'tra_gop_0_phan_tram', 'uu_dai_student', 'uu_dai_khac', 'danh_gia']);
    });
}
};
