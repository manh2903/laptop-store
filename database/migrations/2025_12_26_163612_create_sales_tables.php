<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. MÃ GIẢM GIÁ
        if (!Schema::hasTable('ma_giam_gia')) {
            Schema::create('ma_giam_gia', function (Blueprint $table) {
                $table->id();
                $table->string('ma_code', 50)->unique();
                $table->string('loai_giam_gia', 20)->default('money');
                $table->integer('gia_tri_giam');
                $table->integer('don_hang_toi_thieu')->default(0);
                $table->integer('giam_toi_da')->nullable();
                $table->integer('so_luong')->default(0);
                $table->integer('da_su_dung')->default(0);
                $table->dateTime('ngay_bat_dau')->nullable();
                $table->dateTime('ngay_ket_thuc')->nullable();
                $table->tinyInteger('trang_thai')->default(1);
                $table->timestamps();
            });
        }

        // 2. GIỎ HÀNG
        if (!Schema::hasTable('gio_hang')) {
            Schema::create('gio_hang', function (Blueprint $table) {
                $table->id();
                // [FIX] Dùng integer để khớp với bảng nguoi_dung cũ
                $table->integer('id_nguoi_dung'); 
                $table->timestamps();

                $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            });
        }

        // 3. CHI TIẾT GIỎ HÀNG
        if (!Schema::hasTable('chi_tiet_gio_hang')) {
            Schema::create('chi_tiet_gio_hang', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_gio_hang');
                
                // [FIX] Dùng integer để khớp với bảng san_pham cũ
                $table->integer('id_san_pham');    
                
                $table->integer('so_luong')->default(1);
                $table->integer('don_gia')->default(0);
                $table->timestamps();

                $table->foreign('id_gio_hang')->references('id')->on('gio_hang')->onDelete('cascade');
                $table->foreign('id_san_pham')->references('id')->on('san_pham')->onDelete('cascade');
            });
        }

        // 4. ĐƠN HÀNG
        if (!Schema::hasTable('don_hang')) {
            Schema::create('don_hang', function (Blueprint $table) {
                $table->id();
                // [FIX] Dùng integer
                $table->integer('id_nguoi_dung'); 
                
                $table->string('ma_don_hang', 50)->unique();
                $table->string('ten_nguoi_nhan');
                $table->string('sdt_nguoi_nhan', 20);
                $table->string('dia_chi_giao_hang');
                $table->string('email_nguoi_nhan')->nullable();

                $table->unsignedBigInteger('id_ma_giam_gia')->nullable();
                $table->integer('so_tien_giam')->default(0);
                $table->integer('tong_tien');
                $table->string('phuong_thuc_thanh_toan')->default('COD');
                
                $table->tinyInteger('trang_thai')->default(0);
                $table->text('ghi_chu')->nullable();
                $table->timestamps();

                $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung');
                $table->foreign('id_ma_giam_gia')->references('id')->on('ma_giam_gia');
            });
        }

        // 5. CHI TIẾT ĐƠN HÀNG
        if (!Schema::hasTable('chi_tiet_don_hang')) {
            Schema::create('chi_tiet_don_hang', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_don_hang');
                
                // [FIX] Dùng integer
                $table->integer('id_san_pham'); 
                
                $table->integer('so_luong');
                $table->integer('don_gia');
                $table->integer('thanh_tien');

                $table->foreign('id_don_hang')->references('id')->on('don_hang')->onDelete('cascade');
                $table->foreign('id_san_pham')->references('id')->on('san_pham');
            });
        }

        // 6. LỊCH SỬ DÙNG MÃ
        if (!Schema::hasTable('lich_su_dung_ma')) {
            Schema::create('lich_su_dung_ma', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_ma_giam_gia');
                
                // [FIX] Dùng integer
                $table->integer('id_nguoi_dung');
                
                $table->unsignedBigInteger('id_don_hang');
                $table->timestamp('ngay_tao')->useCurrent();

                $table->foreign('id_ma_giam_gia')->references('id')->on('ma_giam_gia');
                $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung');
                $table->foreign('id_don_hang')->references('id')->on('don_hang');
            });
        }

        // 7. ĐÁNH GIÁ
        if (!Schema::hasTable('danh_gia')) {
            Schema::create('danh_gia', function (Blueprint $table) {
                $table->id();
                $table->integer('id_nguoi_dung'); // [FIX]
                $table->integer('id_san_pham');   // [FIX]
                $table->integer('so_sao')->default(5);
                $table->text('noi_dung')->nullable();
                $table->tinyInteger('trang_thai')->default(1);
                $table->timestamps();

                $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
                $table->foreign('id_san_pham')->references('id')->on('san_pham')->onDelete('cascade');
            });
        }
        
        // 8. YÊU THÍCH
        if (!Schema::hasTable('yeu_thich')) {
            Schema::create('yeu_thich', function (Blueprint $table) {
                $table->id();
                $table->integer('id_nguoi_dung'); // [FIX]
                $table->integer('id_san_pham');   // [FIX]
                $table->timestamp('ngay_tao')->useCurrent();

                $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
                $table->foreign('id_san_pham')->references('id')->on('san_pham')->onDelete('cascade');
            });
        }
        
        // 9. TIN TỨC
        if (!Schema::hasTable('tin_tuc')) {
            Schema::create('tin_tuc', function (Blueprint $table) {
                $table->id();
                $table->string('tieu_de');
                $table->string('slug')->unique();
                $table->string('hinh_anh')->nullable();
                $table->text('tom_tat')->nullable();
                $table->longText('noi_dung');
                $table->string('tac_gia')->nullable();
                $table->integer('luot_xem')->default(0);
                $table->tinyInteger('trang_thai')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('tin_tuc');
        Schema::dropIfExists('yeu_thich');
        Schema::dropIfExists('danh_gia');
        Schema::dropIfExists('lich_su_dung_ma');
        Schema::dropIfExists('chi_tiet_don_hang');
        Schema::dropIfExists('don_hang');
        Schema::dropIfExists('chi_tiet_gio_hang');
        Schema::dropIfExists('gio_hang');
        Schema::dropIfExists('ma_giam_gia');
    }
};