<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    protected $table = 'gio_hang';
    protected $fillable = ['id_nguoi_dung'];

    /**
     * QUAN TRỌNG: Định nghĩa quan hệ ngược lại
     * Giúp Admin lấy được tên khách hàng từ giỏ hàng
     */
    public function nguoiDung()
    {
        // Liên kết với Model NguoiDung bạn vừa sửa
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    /**
     * Quan hệ với chi tiết sản phẩm trong giỏ
     */
    public function chiTiet()
    {
        return $this->hasMany(ChiTietGioHang::class, 'id_gio_hang');
    }
}