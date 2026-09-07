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
    Schema::table('danh_muc', function (Blueprint $table) {
        // 1. Thêm cột 'loai' để phân chia khu vực
        if (!Schema::hasColumn('danh_muc', 'loai')) {
            $table->string('loai')->default('menu')->index(); 
            // index() giúp truy vấn nhanh hơn
        }
        
        // 2. Thêm cột 'icon' cho Menu
        if (!Schema::hasColumn('danh_muc', 'icon')) {
            $table->string('icon')->nullable();
        }

        // 3. Thêm cột 'hinh_anh' cho Logo thương hiệu
        if (!Schema::hasColumn('danh_muc', 'hinh_anh')) {
            $table->string('hinh_anh')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
