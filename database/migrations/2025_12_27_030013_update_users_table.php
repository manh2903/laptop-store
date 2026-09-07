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
    Schema::table('users', function (Blueprint $table) {
        // Thêm các cột còn thiếu nếu chưa có
        if (!Schema::hasColumn('users', 'so_dien_thoai')) {
            $table->string('so_dien_thoai')->nullable();
        }
        if (!Schema::hasColumn('users', 'id_vai_tro')) {
            $table->integer('id_vai_tro')->default(2); // 1: Admin, 2: Khách
        }
        if (!Schema::hasColumn('users', 'status')) {
            $table->tinyInteger('status')->default(1); // 1: Active
        }
        if (!Schema::hasColumn('users', 'dia_chi')) {
            $table->text('dia_chi')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
