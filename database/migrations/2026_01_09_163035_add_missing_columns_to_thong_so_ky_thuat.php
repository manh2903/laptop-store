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
        // Màn hình
        if (!Schema::hasColumn('thong_so_ky_thuat', 'tan_so_quet')) {
            $table->string('tan_so_quet')->nullable()->after('man_hinh');
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'tam_nen')) {
            $table->string('tam_nen')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'do_phan_giai')) {
            $table->string('do_phan_giai')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'kich_thuoc_man_hinh')) {
            $table->string('kich_thuoc_man_hinh')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'cong_nghe_man_hinh')) {
            $table->string('cong_nghe_man_hinh')->nullable();
        }

        // CPU & GPU
        if (!Schema::hasColumn('thong_so_ky_thuat', 'cong_nghe_cpu')) {
            $table->string('cong_nghe_cpu')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'so_nhan')) {
            $table->string('so_nhan')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'loai_card_do_hoa')) {
            $table->string('loai_card_do_hoa')->nullable();
        }

        // Kết nối & Khác
        if (!Schema::hasColumn('thong_so_ky_thuat', 'cong_giao_tiep')) {
            $table->string('cong_giao_tiep')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'ket_noi_khong_day')) {
            $table->string('ket_noi_khong_day')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'webcam')) {
            $table->string('webcam')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'cong_nghe_am_thanh')) {
            $table->string('cong_nghe_am_thanh')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'he_dieu_hanh')) {
            $table->string('he_dieu_hanh')->nullable();
        }

        // Thiết kế
        if (!Schema::hasColumn('thong_so_ky_thuat', 'chat_lieu')) {
            $table->string('chat_lieu')->nullable();
        }
        if (!Schema::hasColumn('thong_so_ky_thuat', 'kich_thuoc')) {
            $table->string('kich_thuoc')->nullable(); // Có thể dùng chung với kich_thuoc_tong_the
        }
        
        // Cột JSON quan trọng (Nếu chưa có)
        if (!Schema::hasColumn('thong_so_ky_thuat', 'thong_so_tuy_chinh')) {
            $table->json('thong_so_tuy_chinh')->nullable();
        }
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
