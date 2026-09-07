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
        // Cột này sẽ lưu dạng: [{"key": "Webcam", "val": "HD 720p"}, {"key": "Bảo mật", "val": "Vân tay"}]
        $table->json('thong_so_tuy_chinh')->nullable(); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thong_so_ky_thuat', function (Blueprint $table) {
            //
        });
    }
};
