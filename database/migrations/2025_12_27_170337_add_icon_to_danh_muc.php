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
        // Thêm cột icon nếu chưa có
        if (!Schema::hasColumn('danh_muc', 'icon')) {
            $table->string('icon')->nullable()->after('ten_danh_muc');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('danh_muc', function (Blueprint $table) {
            //
        });
    }
};
