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
    Schema::table('hinh_anh_san_pham', function (Blueprint $table) {
        // 'gallery' = Ảnh thường, 'feature' = Ảnh tính năng
        $table->string('loai')->default('gallery')->after('duong_dan_anh'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hinh_anh_san_pham', function (Blueprint $table) {
            //
        });
    }
};
