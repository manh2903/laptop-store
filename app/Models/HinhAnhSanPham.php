<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HinhAnhSanPham extends Model
{
    use HasFactory;

    protected $table = 'hinh_anh_san_pham';
    
    // Nếu bảng này không có cột created_at/updated_at thì để false
    public $timestamps = true; 

   protected $fillable = [
    'id_san_pham',
    'duong_dan_anh', // Dùng làm ảnh Thường (Medium)
    'anh_nho',       // Dùng làm ảnh Nhỏ (Small)
    'anh_lon',       // Dùng làm ảnh Lớn (Large)
    'loai',          // Vẫn giữ để phân biệt feature/gallery
];

    // Liên kết ngược về sản phẩm (Optional)
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham');
    }
}