<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    
    // Cấu hình timestamps theo tên cột trong DB của bạn
    public $timestamps = true;
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';

    protected $fillable = [
        // --- Thông tin cơ bản ---
        'ten_san_pham', 
        'slug', 
        'ma_sku', 
        'id_danh_muc', 
        'id_thuong_hieu', 
        
        // --- Giá và Kho ---
        'gia_ban', 
        'gia_khuyen_mai', 
        'so_luong_ton', 
        
        // --- Ảnh & Media (QUAN TRỌNG: Phải có dòng dưới) ---
        'anh_dai_dien', 
        'video_url',          // <--- MỚI: Link video
        'tinh_nang_noi_bat',  // <--- MỚI: Nội dung text tính năng
        
        // --- Thông tin mô tả ---
        'mo_ta', 
        'thong_tin_them', 
        'uu_dai_student', 
        'uu_dai_khac', 
        
        // --- Trạng thái & Chỉ số ---
        'trang_thai', 
        'is_flash_sale', 
        'tra_gop_0_phan_tram',
        'so_luot_danh_gia',   // hoặc 'danh_gia' tùy DB bạn
        'so_luot_xem',         // hoặc 'luot_xem' tùy DB bạn

        // --- MỚI THÊM: Giá cũ và Trợ giá ---
        'gia_thu_cu',   // Thêm dòng này
        'tro_gia'    // Thêm dòng này
    ];

    // --- CÁC RELATIONSHIP (LIÊN KẾT) ---

    // 1. Liên kết Danh mục (Nhiều sản phẩm thuộc 1 danh mục)
    public function danhMuc() {
        return $this->belongsTo(DanhMuc::class, 'id_danh_muc');
    }

    // 2. Liên kết Thương hiệu (Nhiều sản phẩm thuộc 1 thương hiệu)
    public function thuongHieu() {
        return $this->belongsTo(ThuongHieu::class, 'id_thuong_hieu');
    }

    // 3. Liên kết Thông số kỹ thuật (1-1)
    public function thongSo()
    {
        return $this->hasOne(ThongSoKyThuat::class, 'id_san_pham', 'id');
    }

    // 4. Liên kết Ảnh phụ (1-nhiều)
    public function hinhAnh()
    {
        return $this->hasMany(HinhAnhSanPham::class, 'id_san_pham', 'id');
    }

        // Trong class SanPham
    public function bienThe()
    {
        return $this->hasMany(BienTheSanPham::class, 'id_san_pham');
    }

        public function cauHinh()
    {
        return $this->hasMany(CauHinhSanPham::class, 'id_san_pham');
    }
}