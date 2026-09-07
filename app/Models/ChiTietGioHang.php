<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietGioHang extends Model
{
    protected $table = 'chi_tiet_gio_hang';
    
    // Tắt timestamps nếu bảng của bạn không có created_at/updated_at
    public $timestamps = true; 

    protected $fillable = [
        'id_gio_hang',
        'id_san_pham',
        'so_luong'
    ];

    // QUAN TRỌNG: Phải có hàm này để fix lỗi "Call to undefined method"
    public function gioHang()
    {
        return $this->belongsTo(GioHang::class, 'id_gio_hang');
    }

    // Để hiển thị tên/ảnh sản phẩm trong giỏ sau này
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'id_san_pham');
    }
}