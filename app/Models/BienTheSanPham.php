<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienTheSanPham extends Model
{
    use HasFactory;

    // Khai báo tên bảng (để chắc chắn)
    protected $table = 'bien_the_san_pham';

    // Cho phép lưu các cột này
    protected $fillable = [
        'id_san_pham', 
        'ten_mau', 
        'gia_ban', 
        'anh_mau'
    ];

    // Quan hệ ngược về sản phẩm (Tùy chọn, thêm cho đầy đủ)
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham');
    }
}