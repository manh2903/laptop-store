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
        $table->text('tinh_nang_noi_bat')->nullable(); // Lưu nội dung HTML/Text
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('san_pham', function (Blueprint $table) {
            //
        });
    }
};
