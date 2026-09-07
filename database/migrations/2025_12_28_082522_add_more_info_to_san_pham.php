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
        // Cột chứa text "Hàng mới về" hoặc tag lạ (cho class .product-info)
        $table->string('thong_tin_them')->nullable()->after('ten_san_pham'); 
        
        // Cột chứa số tiền giảm Smember (cho class .block-smem-price)
        // Nếu có nhập số (VD: 140000) thì hiện dòng Smember, không thì ẩn
        $table->decimal('giam_smember', 15, 0)->nullable()->after('gia_khuyen_mai');
    });
}

public function down()
{
    Schema::table('san_pham', function (Blueprint $table) {
        $table->dropColumn(['thong_tin_them', 'giam_smember']);
    });
}
};
